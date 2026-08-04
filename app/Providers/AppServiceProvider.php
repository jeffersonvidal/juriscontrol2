<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Models
use App\Models\Tag;
use App\Models\Company;
use App\Models\Client;
use App\Models\SystemOption;

// Policies
use App\Modules\Tags\Policies\TagPolicy;
use App\Modules\Clients\Policies\ClientPolicy;
use App\Modules\DriveSettings\Policies\DriveSettingsPolicy;

// Observers
use App\Observers\SystemOptionInitializerObserver;

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
        // ==========================================
        // REGISTRO DE POLICIES (Modular Monolith)
        // ==========================================
        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(Tag::class, TagPolicy::class);
        Gate::policy(SystemOption::class, DriveSettingsPolicy::class); // Policy do Google Drive

        // ==========================================
        // GATE GLOBAL: Super Admin Bypass
        // ==========================================
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return true;
            }
            return null; // segue para a policy normal
        });

        // ==========================================
        // OBSERVERS
        // ==========================================
        // Cria system options ao cadastrar empresa
        Company::observe(SystemOptionInitializerObserver::class);

        // ==========================================
        // AUDITORIA (owen-it/laravel-auditing)
        // ==========================================
        // FORÇA o resolver correto em tempo de execução, ignorando qualquer 
        // configuração quebrada, em cache ou no arquivo .env
        config(['audit.user.resolver' => \OwenIt\Auditing\Resolvers\UserResolver::class]);
    }
}