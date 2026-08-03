<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SystemOptionPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definir as permissões do módulo
        $permissions = [
            'system-option.view',
            'system-option.create',
            'system-option.edit',
            'system-option.delete',
        ];

        // 2. Criar ou atualizar as permissões no banco de dados
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName],
                ['guard_name' => 'web']
            );
        }

        // 3. Atribuir às roles de administração (super-admin e admin, por segurança)
        $roles = ['super-admin', 'admin'];
        
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(
                ['name' => $roleName],
                ['guard_name' => 'web']
            );
            
            // givePermissionTo ignora permissões já atribuídas, evitando erros
            $role->givePermissionTo($permissions);
        }
    }
}