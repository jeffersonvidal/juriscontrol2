<?php

namespace Database\Seeders;

use App\Modules\Rbac\Enums\Role;
use App\Modules\Rbac\Services\RolePermissionService;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\PermissionRegistrar;

/**
 * RoleSeeder
 * --------------------------------------------------------
 * Cria os roles e associa as permissões conforme o mapeamento
 * centralizado em RolePermissionService.
 *
 * Princípio: Single Responsibility — o seeder apenas orquestra.
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa cache antes de manipular permissões/roles
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (Role::cases() as $roleEnum) {
            // Cria ou recupera o role
            $role = SpatieRole::firstOrCreate(
                ['name' => $roleEnum->value, 'guard_name' => 'web']
            );

            // Busca as permissões mapeadas para este role
            $permissions = RolePermissionService::permissionsFor($roleEnum);

            // Sincroniza (remove as que não estão mais, adiciona as novas)
            $role->syncPermissions($permissions);
        }

        $this->command->info('✅ Roles criados e permissões sincronizadas.');
    }
}