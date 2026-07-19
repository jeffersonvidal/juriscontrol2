<?php

namespace App\Modules\Rbac\Enums;

/**
 * Enum Permission
 * --------------------------------------------------------
 * Catálogo ÚNICO de todas as permissões do sistema.
 * Padrão: {modulo}.{recurso}.{acao}
 *
 * Regras do playbook:
 *  - Permissões granulares
 *  - Usado em seeders, policies e formulários
 *  - NUNCA escrever permissões como string solta no código
 */
enum Permission: string
{
    // ========== COMPANIES (apenas super-admin) ==========
    case COMPANIES_VIEW   = 'companies.view';
    case COMPANIES_CREATE = 'companies.create';
    case COMPANIES_UPDATE = 'companies.update';
    case COMPANIES_DELETE = 'companies.delete';

    // ========== USERS (gestão de usuários do tenant) ==========
    case USERS_VIEW   = 'users.view';
    case USERS_CREATE = 'users.create';
    case USERS_UPDATE = 'users.update';
    case USERS_DELETE = 'users.delete';

    // ========== CLIENTS (clientes do escritório) ==========
    case CLIENTS_VIEW   = 'clients.view';
    case CLIENTS_CREATE = 'clients.create';
    case CLIENTS_UPDATE = 'clients.update';
    case CLIENTS_DELETE = 'clients.delete';

    // ========== LEGAL CASES (processos/casos jurídicos) ==========
    case LEGAL_CASES_VIEW   = 'legal.cases.view';
    case LEGAL_CASES_CREATE = 'legal.cases.create';
    case LEGAL_CASES_UPDATE = 'legal.cases.update';
    case LEGAL_CASES_DELETE = 'legal.cases.delete';

    // ========== HEARINGS (audiências) ==========
    case HEARINGS_VIEW   = 'hearings.view';
    case HEARINGS_CREATE = 'hearings.create';
    case HEARINGS_UPDATE = 'hearings.update';
    case HEARINGS_DELETE = 'hearings.delete';

    // ========== DEADLINES (prazos processuais) ==========
    case DEADLINES_VIEW   = 'deadlines.view';
    case DEADLINES_CREATE = 'deadlines.create';
    case DEADLINES_UPDATE = 'deadlines.update';
    case DEADLINES_DELETE = 'deadlines.delete';

    // ========== DOCUMENTS (documentos do processo) ==========
    case DOCUMENTS_VIEW   = 'documents.view';
    case DOCUMENTS_CREATE = 'documents.create';
    case DOCUMENTS_UPDATE = 'documents.update';
    case DOCUMENTS_DELETE = 'documents.delete';

    // ========== TASKS (tarefas) ==========
    case TASKS_VIEW   = 'tasks.view';
    case TASKS_CREATE = 'tasks.create';
    case TASKS_UPDATE = 'tasks.update';
    case TASKS_DELETE = 'tasks.delete';

    // ========== FINANCIAL - INCOMES (receitas) ==========
    case FINANCIAL_INCOMES_VIEW   = 'financial.incomes.view';
    case FINANCIAL_INCOMES_CREATE = 'financial.incomes.create';
    case FINANCIAL_INCOMES_UPDATE = 'financial.incomes.update';
    case FINANCIAL_INCOMES_DELETE = 'financial.incomes.delete';

    // ========== FINANCIAL - EXPENSES (despesas) ==========
    case FINANCIAL_EXPENSES_VIEW   = 'financial.expenses.view';
    case FINANCIAL_EXPENSES_CREATE = 'financial.expenses.create';
    case FINANCIAL_EXPENSES_UPDATE = 'financial.expenses.update';
    case FINANCIAL_EXPENSES_DELETE = 'financial.expenses.delete';

    // ========== REPORTS (relatórios) ==========
    case REPORTS_VIEW = 'reports.view';

    // ========== SETTINGS (configurações do tenant) ==========
    case SETTINGS_VIEW   = 'settings.view';
    case SETTINGS_UPDATE = 'settings.update';

    // ========== ROLES & PERMISSIONS (gestão de acesso) ==========
    case ROLES_VIEW       = 'roles.view';
    case ROLES_MANAGE     = 'roles.manage';       // create/update/delete
    case PERMISSIONS_VIEW = 'permissions.view';

    // ========== AUDIT LOGS (logs de auditoria) ==========
    case AUDIT_LOGS_VIEW = 'audit.logs.view';

    /**
     * Retorna TODAS as permissões como array de strings.
     * Usado nos seeders.
     */
    public static function allValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Retorna permissões agrupadas por módulo (útil para UI de roles).
     */
    public static function grouped(): array
    {
        $grouped = [];
        foreach (self::cases() as $permission) {
            // Extrai o módulo (primeira parte antes do primeiro ".")
            $module = explode('.', $permission->value)[0];
            $grouped[$module][] = $permission->value;
        }
        return $grouped;
    }
}