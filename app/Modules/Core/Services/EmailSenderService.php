<?php

namespace App\Modules\Core\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Modules\Core\Mail\GenericMailable;
use Exception;

/**
 * Classe EmailSenderService
 * 
 * Responsabilidade Única (SRP): Orquestrar e despachar e-mails, 
 * centralizando o tratamento de erros e a decisão entre envio síncrono ou assíncrono (fila).
 */
class EmailSenderService
{
    /**
     * Envia um e-mail para um destinatário específico.
     *
     * @param string $to      Endereço de e-mail do destinatário.
     * @param string $subject Assunto do e-mail.
     * @param string $view    Nome da view Blade (ex: 'emails.recuperacao-senha').
     * @param array  $data    Dados dinâmicos a serem passados para a view.
     * @param bool   $queue   Se true (padrão), envia para a fila (Async First). Se false, envia sincronamente.
     * @return bool           Retorna true se o despacho foi bem-sucedido, false caso contrário.
     */
    public function send(
        string $to, 
        string $subject, 
        string $view, 
        array $data = [], 
        bool $queue = true
    ): bool {
        try {
            // Instancia o Mailable genérico com os dados fornecidos
            $mailable = new GenericMailable($subject, $view, $data);

            // Aplica a regra de negócio: Async First (padrão em fila, salvo se forçado o contrário)
            if ($queue) {
                // Despacha para a fila padrão (configurada em queue.php)
                Mail::to($to)->queue($mailable);
            } else {
                // Envio síncrono (bloqueante), útil para testes ou e-mails críticos imediatos
                Mail::to($to)->send($mailable);
            }

            return true;

        } catch (Exception $e) {
            // Log do erro com contexto para facilitar o debugging e observabilidade
            Log::error('Falha ao enviar e-mail', [
                'to' => $to,
                'subject' => $subject,
                'view' => $view,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return false;
        }
    }
}