<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\JustificacionRepositoryInterface::class,
            \App\Repositories\JustificacionRepository::class
        );

        $this->app->bind(
            \App\Domain\Justificacion\Observer\Contracts\JustificationSubject::class,
            \App\Domain\Justificacion\Observer\JustificationDecisionSubject::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
