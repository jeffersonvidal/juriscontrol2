<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Modules\Clients\Policies\ClientPolicy;
use Illuminate\Support\Facades\Gate;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registra policies (adicionar novas conforme módulos forem criados)
        Gate::policy(\App\Models\Client::class, ClientPolicy::class);

        // Gate global: super-admin bypass (acesso total)
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return true;
            }
            return null; // segue para a policy normal
        });
    }
}
