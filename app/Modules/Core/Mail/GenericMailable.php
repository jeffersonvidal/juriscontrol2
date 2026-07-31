<?php

namespace App\Modules\Core\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Classe GenericMailable
 * 
 * Responsabilidade Única (SRP): Formatar a estrutura do e-mail (Assunto, View e Dados).
 * Implementa ShouldQueue para garantir que o processamento pesado de renderização 
 * da view não bloqueie a resposta HTTP da aplicação (Async First).
 */
class GenericMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Construtor com promoção de propriedades (PHP 8.2+)
     *
     * @param string $subject Assunto do e-mail.
     * @param string $view    Caminho da view Blade (ex: 'emails.fatura-vencimento').
     * @param array  $data    Array associativo com as variáveis para a view.
     */
    public function __construct(
        public string $subject,
        public string $view,
        public array $data = []
    ) {
        // Define a fila padrão para 'notifications' conforme playbook (Async First)
        // Certifique-se de que a fila 'notifications' está configurada no config/queue.php
        $this->onQueue('notifications');
    }

    /**
     * Define o envelope do e-mail (remetente, assunto).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Define o conteúdo do e-mail (qual view usar e quais dados passar).
     */
    public function content(): Content
    {
        return new Content(
            view: $this->view,
            with: $this->data, // Injeta as variáveis no contexto da view Blade
        );
    }

    /**
     * Anexos não são suportados por padrão nesta classe genérica.
     * Se precisar de anexos no futuro, crie um Mailable específico (ex: InvoiceMailable)
     * para manter o SRP, ou adicione um parâmetro $attachments aqui.
     */
    public function attachments(): array
    {
        return [];
    }
}