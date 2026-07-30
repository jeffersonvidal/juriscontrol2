<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TagPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Define as permissões do módulo de Tags
        $permissions = [
            'tags.view',
            'tags.create',
            'tags.edit',
            'tags.delete',
        ];

        // 2. Cria cada permissão no banco (guard 'web' por padrão)
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 3. Atribui todas as permissões ao papel 'admin' (ou 'super-admin')
        // Ajuste o nome do papel conforme o seu sistema
        $adminRole = Role::where('name', 'admin')->first();
        
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
            $this->command->info('Permissões de Tags atribuídas ao papel "admin".');
        } else {
            $this->command->warn('Papel "admin" não encontrado. Atribua as permissões manualmente.');
        }
    }
}