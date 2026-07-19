<?php

namespace Database\Seeders;

use App\Modules\Rbac\Enums\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission as SpatiePermission;
use Spatie\Permission\PermissionRegistrar;

/**
 * PermissionSeeder
 * --------------------------------------------------------
 * Cria TODAS as permissões do sistema no banco.
 * Usa syncPermissions para garantir idempotência
 * (pode ser executado N vezes sem duplicar).
 *
 * Regra do playbook: "Permissões granulares {modulo}.{recurso}.{acao}"
 */
class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa o cache de permissões (evita stale data)
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Cria ou atualiza cada permissão (upsert)
        foreach (Permission::allValues() as $permissionName) {
            SpatiePermission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => 'web']
            );
        }

        $this->command->info('✅ Permissões criadas/atualizadas com sucesso.');
    }
}