<?php

namespace App\Providers;

use App\Domain\Justificacion\Observer\Contracts\JustificationSubject;
use App\Domain\Justificacion\Observer\JustificationDecisionSubject;
use App\Domain\Justificacion\Observer\NotificationObserver;
use App\Domain\Justificacion\Services\JustificationStatusNotifier;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * El Sujeto se registra como singleton por request.
     * No conoce a sus observadores; solo expone attach/detach/notify.
     */
    public function register(): void
    {
        $this->app->singleton(JustificationSubject::class, function ($app) {
            return new JustificationDecisionSubject;
        });
    }

    public function boot(): void
    {
        // Adjuntar observadores por defecto
        $this->app->afterResolving(JustificationSubject::class, function (JustificationSubject $subject, $app) {
            // Observador que envía notificación + broadcast en tiempo real
            $subject->attach(new NotificationObserver($app->make(JustificationStatusNotifier::class)));
       
            // (Se pueden añadir más suscriptores sin tocar el sujeto)
        });
    }
}
