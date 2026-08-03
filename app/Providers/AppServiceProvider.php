<?php

namespace App\Providers;

use App\Models\Tag;
use App\Modules\Tags\Policies\TagPolicy;
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
        Gate::policy(Tag::class, TagPolicy::class);

        // Gate global: super-admin bypass (acesso total)
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return true;
            }
            return null; // segue para a policy normal
        });

        // FORÇA o resolver correto em tempo de execução, ignorando qualquer 
        // configuração quebrada, em cache ou no arquivo .env
        config(['audit.user.resolver' => \OwenIt\Auditing\Resolvers\UserResolver::class]);

    }
}
