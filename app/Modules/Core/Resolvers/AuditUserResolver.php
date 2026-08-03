<?php

namespace App\Modules\Core\Resolvers;

use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Resolvers\UserResolver; // <-- CORREÇÃO: Auditing

class AuditUserResolver extends UserResolver
{
    public static function resolve($driver = null)
    {
        return Auth::id();
    }

    public static function resolveType($driver = null)
    {
        return Auth::user() ? get_class(Auth::user()) : null;
    }

    public static function resolveCompanyId($driver = null)
    {
        return Auth::user()?->company_id;
    }
}