<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyAddress;
use Illuminate\Database\Seeder;

/**
 * CompanySeeder
 * --------------------------------------------------------
 * Cria uma empresa de teste com endereço para validar o fluxo multi-tenant.
 * 
 * CORREÇÃO: usa 'cnpj_cpf' ao invés de 'document' (novo schema).
 */
class CompanySeeder extends Seeder
{
    public function run(): void
    {
        // Guard Clause: não recria se já existir
        // CORREÇÃO: campo 'cnpj_cpf' ao invés de 'document'
        if (Company::withoutGlobalScopes()->where('cnpj_cpf', '12.345.678/0001-90')->exists()) {
            $this->command->warn('⚠️  Empresa de teste já existe. Pulando.');
            return;
        }

        $company = Company::create([
            'trade_name'        => 'Escritório Modelo',
            'trade_name_slug'   => 'escritorio-modelo',
            'corporate_reason'  => 'Escritório Modelo Advogados Associados',
            'email'             => 'contato@escritoriomodelo.com.br',
            'cnpj_cpf'          => '12.345.678/0001-90',
            'phone'             => '(11) 3456-7890',
            'user_id'           => null, // Será preenchido pelo AdminUserSeeder
            'is_active'         => true,
        ]);

        // Cria endereço principal
        CompanyAddress::create([
            'company_id' => $company->id,
            'label'      => 'Matriz',
            'street'     => 'Avenida Paulista',
            'number'     => '1000',
            'complement' => 'Sala 1001',
            'district'   => 'Bela Vista',
            'city'       => 'São Paulo',
            'state'      => 'SP',
            'zip_code'   => '01310-100',
            'country'    => 'Brasil',
            'is_default' => true,
        ]);

        $this->command->info("✅ Empresa criada: {$company->trade_name} (ID: {$company->id})");
    }
}