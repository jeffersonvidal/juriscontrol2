<?php

namespace App\Modules\Rbac\Enums;

/**
 * Enum Role
 * --------------------------------------------------------
 * Catálogo de roles (perfis) do sistema.
 * Cada role define um conjunto de permissões.
 */
enum Role: string
{
    case SUPER_ADMIN = 'super-admin';   // Acesso cross-tenant (SaaS owner)
    case ADMIN       = 'admin';         // Administrador do tenant (escritório)
    case LAWYER      = 'lawyer';        // Advogado
    case PARALEGAL   = 'paralegal';     // Assistente/Paralegal
    case INTERN      = 'intern';        // Estagiário
    case FINANCIAL   = 'financial';     // Responsável financeiro
    case CLIENT_VIEW = 'client-view';   // Visualização limitada (cliente externo)

    /**
     * Retorna todos os roles como array de strings.
     */
    public static function allValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}