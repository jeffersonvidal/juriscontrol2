<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

/**
 * CompanySeeder
 * --------------------------------------------------------
 * Cria uma empresa de teste para validar o fluxo multi-tenant.
 */
class CompanySeeder extends Seeder
{
    public function run(): void
    {
        // Guard Clause: não recria se já existir
        if (Company::withoutGlobalScopes()->where('document', '12.345.678/0001-90')->exists()) {
            $this->command->warn('⚠️  Empresa de teste já existe. Pulando.');
            return;
        }

        $company = Company::create([
            'name'        => 'Escritório Modelo Advogados',
            'trade_name'  => 'Escritório Modelo',
            'document'    => '12.345.678/0001-90',
            'email'       => 'contato@escritoremodelo.com.br',
            'phone'       => '(11) 3456-7890',
            'status'      => 'active',
            'trial_ends_at' => now()->addDays(30),
        ]);

        $this->command->info("✅ Empresa criada: {$company->trade_name} (ID: {$company->id})");
    }
}