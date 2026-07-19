<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Rbac\Enums\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * SuperAdminSeeder
 * --------------------------------------------------------
 * Cria o usuário super-admin inicial do SaaS.
 *
 * Regras do playbook:
 *  - Super-admin NÃO tem company_id (acesso cross-tenant)
 *  - Usa Hash::make() para senha segura
 *  - Usa env() para permitir sobrescrita em produção
 */
class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Guard Clause: não recria se já existir
        if (User::where('email', 'admin@saas.com')->exists()) {
            $this->command->warn('⚠️  Super-admin já existe. Pulando criação.');
            return;
        }

        $superAdmin = User::create([
            'name'       => env('SUPER_ADMIN_NAME', 'Super Administrador'),
            'email'      => env('SUPER_ADMIN_EMAIL', 'admin@saas.com'),
            'password'   => Hash::make(env('SUPER_ADMIN_PASSWORD', 'ChangeMe@123')),
            'company_id' => null, // CRÍTICO: super-admin não pertence a tenant
            'status'     => 'active',
        ]);

        // Atribui o role super-admin
        $superAdmin->assignRole(Role::SUPER_ADMIN->value);

        $this->command->info('✅ Super-admin criado com sucesso.');
        $this->command->line("   E-mail: {$superAdmin->email}");
        $this->command->line('   Senha: (definida via env SUPER_ADMIN_PASSWORD)');
    }
}