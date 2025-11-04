<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Service Provider: AppServiceProvider
 * Capa: Infrastructure
 * Proveedor de servicios de la aplicación
 */
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
        // Registrar componentes de vista en el nuevo namespace
        $this->loadViewComponentsAs('', [
            'app-layout' => \App\Presentation\View\Components\AppLayout::class,
            'guest-layout' => \App\Presentation\View\Components\GuestLayout::class,
        ]);
    }
}

