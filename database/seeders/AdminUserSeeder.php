<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use App\Modules\Rbac\Enums\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * AdminUserSeeder
 * --------------------------------------------------------
 * Cria um usuário admin para a empresa de teste.
 * Credenciais: admin@escritorio.com / ChangeMe@123
 * 
 * CORREÇÃO: usa 'cnpj_cpf' ao invés de 'document' (novo schema).
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Busca a empresa criada pelo CompanySeeder
        // CORREÇÃO: campo 'cnpj_cpf' ao invés de 'document'
        $company = Company::withoutGlobalScopes()
                          ->where('cnpj_cpf', '12.345.678/0001-90')
                          ->first();

        if (! $company) {
            $this->command->error('❌ Empresa de teste não encontrada. Rode CompanySeeder primeiro.');
            return;
        }

        // Guard Clause: não recria se já existir
        if (User::withoutGlobalScopes()->where('email', 'admin@escritorio.com')->exists()) {
            $this->command->warn('⚠️  Usuário admin já existe. Pulando.');
            return;
        }

        $admin = User::create([
            'name'       => 'Administrador',
            'email'      => 'admin@escritorio.com',
            'password'   => Hash::make('ChangeMe@123'),
            'company_id' => $company->id,
            'status'     => 'active',
        ]);

        // Atribui o role admin (acesso total dentro do tenant)
        $admin->assignRole(Role::ADMIN->value);

        // Atualiza o user_id da empresa para apontar para o admin recém-criado
        $company->update(['user_id' => $admin->id]);

        $this->command->info('✅ Usuário admin criado:');
        $this->command->line("   E-mail: {$admin->email}");
        $this->command->line('   Senha: ChangeMe@123');
        $this->command->line("   Empresa: {$company->trade_name}");
    }
}