<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\JustificationApproved;
use App\Events\JustificationRejected;
use App\Listeners\SendJustificationStatusNotification;

/**
 * Registro central de suscripciones: por cada evento,
 * qué observadores (listeners) deben ejecutarse.
 */
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        JustificationApproved::class => [
            SendJustificationStatusNotification::class,
        ],
        JustificationRejected::class => [
            SendJustificationStatusNotification::class,
        ],
    ];
}
