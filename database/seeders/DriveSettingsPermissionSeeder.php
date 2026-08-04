<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DriveSettingsPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Cria as permissões do módulo DriveSettings e atribui ao role admin
     */
    public function run(): void
    {
        // Resetar cache de permissões
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar permissões do módulo
        $permissions = [
            'drive_settings.view',
            'drive_settings.create',
            'drive_settings.edit',
            'drive_settings.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Atribuir permissões ao role admin (ou super-admin)
        $adminRole = Role::where('name', 'admin')->first();
        
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        $this->command->info('Permissões do módulo DriveSettings criadas com sucesso!');
    }
}