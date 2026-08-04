<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class SystemOptionsPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Definir as permissões padrão do módulo System Options
        $permissions = [
            'system-options.view',
            'system-options.create',
            'system-options.edit',
            'system-options.delete',
        ];

        // 2. Criar as permissões no banco de dados (evita duplicidade)
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission, 'guard_name' => 'web']
            );
        }

        // 3. Atribuir as permissões ao papel 'admin' 
        // ⚠️ ATENÇÃO: Se o seu papel de administrador tiver outro nome (ex: 'super-admin' ou 'owner'), 
        // altere 'admin' abaixo para o nome correto que está na sua tabela 'roles'.
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        // 4. Atribuir diretamente ao usuário principal (ID 1) para garantir acesso imediato durante os testes
        $user = User::find(1);
        if ($user) {
            $user->givePermissionTo($permissions);
        }
    }
}