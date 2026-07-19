<?php

namespace App\Modules\Rbac\Services;

use App\Modules\Rbac\Enums\Permission;
use App\Modules\Rbac\Enums\Role;

/**
 * RolePermissionService
 * --------------------------------------------------------
 * Define o MAPEAMENTO centralizado de permissões por role.
 * Regra do playbook: "NUNCA espalhar permissões pelo código".
 *
 * Princípio: Single Responsibility + Fail Fast.
 */
class RolePermissionService
{
    /**
     * Retorna as permissões associadas a cada role.
     *
     * @return array<string, array<string>>
     */
    public static function map(): array
    {
        return [
            // Super-admin: acesso total (cross-tenant)
            Role::SUPER_ADMIN->value => Permission::allValues(),

            // Admin do tenant: tudo EXETO gestão de companies (SaaS-level)
            Role::ADMIN->value => array_diff(
                Permission::allValues(),
                [
                    Permission::COMPANIES_VIEW->value,
                    Permission::COMPANIES_CREATE->value,
                    Permission::COMPANIES_UPDATE->value,
                    Permission::COMPANIES_DELETE->value,
                ]
            ),

            // Advogado: casos, clientes, audiências, prazos, documentos, tarefas
            Role::LAWYER->value => [
                Permission::CLIENTS_VIEW->value,
                Permission::CLIENTS_CREATE->value,
                Permission::CLIENTS_UPDATE->value,
                Permission::LEGAL_CASES_VIEW->value,
                Permission::LEGAL_CASES_CREATE->value,
                Permission::LEGAL_CASES_UPDATE->value,
                Permission::HEARINGS_VIEW->value,
                Permission::HEARINGS_CREATE->value,
                Permission::HEARINGS_UPDATE->value,
                Permission::DEADLINES_VIEW->value,
                Permission::DEADLINES_CREATE->value,
                Permission::DEADLINES_UPDATE->value,
                Permission::DOCUMENTS_VIEW->value,
                Permission::DOCUMENTS_CREATE->value,
                Permission::DOCUMENTS_UPDATE->value,
                Permission::DOCUMENTS_DELETE->value,
                Permission::TASKS_VIEW->value,
                Permission::TASKS_CREATE->value,
                Permission::TASKS_UPDATE->value,
                Permission::TASKS_DELETE->value,
                Permission::REPORTS_VIEW->value,
            ],

            // Paralegal: visualiza tudo, edita documentos e tarefas
            Role::PARALEGAL->value => [
                Permission::CLIENTS_VIEW->value,
                Permission::LEGAL_CASES_VIEW->value,
                Permission::HEARINGS_VIEW->value,
                Permission::DEADLINES_VIEW->value,
                Permission::DEADLINES_CREATE->value,
                Permission::DEADLINES_UPDATE->value,
                Permission::DOCUMENTS_VIEW->value,
                Permission::DOCUMENTS_CREATE->value,
                Permission::DOCUMENTS_UPDATE->value,
                Permission::DOCUMENTS_DELETE->value,
                Permission::TASKS_VIEW->value,
                Permission::TASKS_CREATE->value,
                Permission::TASKS_UPDATE->value,
            ],

            // Estagiário: apenas visualização + tarefas próprias
            Role::INTERN->value => [
                Permission::CLIENTS_VIEW->value,
                Permission::LEGAL_CASES_VIEW->value,
                Permission::HEARINGS_VIEW->value,
                Permission::DEADLINES_VIEW->value,
                Permission::DOCUMENTS_VIEW->value,
                Permission::TASKS_VIEW->value,
                Permission::TASKS_CREATE->value,
                Permission::TASKS_UPDATE->value,
            ],

            // Financeiro: apenas módulo financeiro + relatórios
            Role::FINANCIAL->value => [
                Permission::CLIENTS_VIEW->value,
                Permission::FINANCIAL_INCOMES_VIEW->value,
                Permission::FINANCIAL_INCOMES_CREATE->value,
                Permission::FINANCIAL_INCOMES_UPDATE->value,
                Permission::FINANCIAL_INCOMES_DELETE->value,
                Permission::FINANCIAL_EXPENSES_VIEW->value,
                Permission::FINANCIAL_EXPENSES_CREATE->value,
                Permission::FINANCIAL_EXPENSES_UPDATE->value,
                Permission::FINANCIAL_EXPENSES_DELETE->value,
                Permission::REPORTS_VIEW->value,
            ],

            // Visualizador externo (cliente): só vê
            Role::CLIENT_VIEW->value => [
                Permission::LEGAL_CASES_VIEW->value,
                Permission::HEARINGS_VIEW->value,
                Permission::DEADLINES_VIEW->value,
                Permission::DOCUMENTS_VIEW->value,
            ],
        ];
    }

    /**
     * Retorna as permissões de um role específico.
     * Guard Clause: lança exceção se role inválido.
     */
    public static function permissionsFor(Role $role): array
    {
        $map = self::map();

        if (! isset($map[$role->value])) {
            throw new \InvalidArgumentException("Role [{$role->value}] não possui mapeamento de permissões.");
        }

        return $map[$role->value];
    }
}