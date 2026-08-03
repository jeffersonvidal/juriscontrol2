<?php

namespace Database\Seeders;

use App\Models\SystemOption;
use Illuminate\Database\Seeder;

class SystemOptionSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            // Configurações de E-mail (Padrão global, company_id = null)
            [
                'option_name' => 'MAIL_MAILER',
                'option_value' => 'smtp',
                'option_description' => 'Protocolo a ser utilizado para envio de e-mails. Ex: smtp',
                'option_status' => 0,
                'company_id' => null,
            ],
            [
                'option_name' => 'MAIL_HOST',
                'option_value' => '',
                'option_description' => 'Servidor responsável por enviar e-mails. Ex: smtp.hostinger.com',
                'option_status' => 0,
                'company_id' => null,
            ],
            [
                'option_name' => 'MAIL_PORT',
                'option_value' => '',
                'option_description' => 'Porta de envio. Ex: 465',
                'option_status' => 0,
                'company_id' => null,
            ],
            [
                'option_name' => 'MAIL_USERNAME',
                'option_value' => '',
                'option_description' => 'Email usado para envio de mensagens. Ex: nao-responda@seuescritorio.com',
                'option_status' => 0,
                'company_id' => null,
            ],
            [
                'option_name' => 'MAIL_PASSWORD',
                'option_value' => '',
                'option_description' => 'Senha do email de envio de mensagens',
                'option_status' => 0,
                'company_id' => null,
            ],
            [
                'option_name' => 'MAIL_ENCRYPTION',
                'option_value' => '',
                'option_description' => 'Tipo de criptografia utilizado. Ex: ssl',
                'option_status' => 0,
                'company_id' => null,
            ],
            [
                'option_name' => 'MAIL_FROM_ADDRESS',
                'option_value' => '',
                'option_description' => 'Email que enviará mensagens. Ex: nao-responda@seuescritorio.com',
                'option_status' => 0,
                'company_id' => null,
            ],
            [
                'option_name' => 'MAIL_FROM_NAME',
                'option_value' => '',
                'option_description' => 'Nome do seu escritório',
                'option_status' => 0,
                'company_id' => null,
            ],
            // Configurações de Plano (Podem ser sobrescritas por empresa, mas iniciam globais)
            [
                'option_name' => 'CONTRACTED_PAN',
                'option_value' => 'basic',
                'option_description' => 'Plano Contratado',
                'option_status' => 1,
                'company_id' => null,
            ],
            [
                'option_name' => 'NUMBER_OF_USERS',
                'option_value' => '3',
                'option_description' => 'Nº de usuários liberados',
                'option_status' => 1,
                'company_id' => null,
            ],
            [
                'option_name' => 'GOOGLE_SET_CLIENT_ID',
                'option_value' => '',
                'option_description' => 'Id do Google Client',
                'option_status' => 1,
                'company_id' => null,
            ],
            [
                'option_name' => 'GOOGLE_SET_CLIENT_SECRET',
                'option_value' => '',
                'option_description' => 'Chave secreta do Google Client',
                'option_status' => 1,
                'company_id' => null,
            ],
            [
                'option_name' => 'GOOGLE_REFRESH_TOKEN',
                'option_value' => '',
                'option_description' => 'Atualização de token automático',
                'option_status' => 1,
                'company_id' => null,
            ],
            [
                'option_name' => 'CLIENTS_GDRIVE_FOLDER_ID',
                'option_value' => '',
                'option_description' => 'Id da pasta clientes do Google Drive',
                'option_status' => 1,
                'company_id' => null,
            ],
        ];

        foreach ($options as $option) {
            // updateOrCreate garante que não haverá duplicidade ao rodar o seeder múltiplas vezes
            SystemOption::updateOrCreate(
                [
                    'option_name' => $option['option_name'],
                    'company_id' => $option['company_id'],
                ],
                $option
            );
        }
    }
}