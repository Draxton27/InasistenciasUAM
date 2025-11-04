<?php

namespace App\Infrastructure\Providers;

use App\Domain\Justificacion\Observer\Contracts\JustificationSubject;
use App\Domain\Justificacion\Observer\JustificationDecisionSubject;
use App\Domain\Justificacion\Observer\NotificationObserver;
use App\Domain\Justificacion\Observer\Services\JustificationStatusNotifier;
use App\Domain\Justificacion\Observer\Services\NotificationGateway as NotificationGatewayInterface;
use App\Domain\Repositories\JustificacionRepositoryInterface;
use App\Domain\Repositories\ProfesorRepositoryInterface;
use App\Domain\Repositories\ClaseRepositoryInterface;
use App\Domain\Repositories\EstudianteRepositoryInterface;
use App\Domain\Repositories\ReprogramacionRepositoryInterface;
use App\Application\Services\JustificacionService;
use App\Application\Services\ProfesorService;
use App\Application\Services\ClaseService;
use App\Application\Services\EstudianteService;
use App\Application\Services\ReprogramacionService;
use App\Application\Services\ReportService;
use App\Infrastructure\Persistence\Eloquent\Repositories\JustificacionRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\ProfesorRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\ClaseRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EstudianteRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\ReprogramacionRepository;
use App\Infrastructure\Notifications\Services\JustificationStatusNotifier as JustificationStatusNotifierImpl;
use App\Infrastructure\Notifications\Services\NotificationGateway as NotificationGatewayImpl;
use Illuminate\Support\ServiceProvider;

/**
 * Service Provider: ObserverServiceProvider
 * Capa: Infrastructure
 * Registra los servicios relacionados con el patrón Observer para justificaciones
 */
class ObserverServiceProvider extends ServiceProvider
{
    /**
     * El Sujeto se registra como singleton por request.
     * No conoce a sus observadores; solo expone attach/detach/notify.
     */
    public function register(): void
    {
               // Registrar repositorios
               $this->app->singleton(JustificacionRepositoryInterface::class, JustificacionRepository::class);
               $this->app->singleton(ProfesorRepositoryInterface::class, ProfesorRepository::class);
               $this->app->singleton(ClaseRepositoryInterface::class, ClaseRepository::class);
               $this->app->singleton(EstudianteRepositoryInterface::class, EstudianteRepository::class);
               $this->app->singleton(ReprogramacionRepositoryInterface::class, ReprogramacionRepository::class);

               // Registrar servicios de aplicación
               $this->app->singleton(JustificacionService::class, function ($app) {
                   return new JustificacionService($app->make(JustificacionRepositoryInterface::class));
               });

               $this->app->singleton(ProfesorService::class, function ($app) {
                   return new ProfesorService($app->make(ProfesorRepositoryInterface::class));
               });

               $this->app->singleton(ClaseService::class, function ($app) {
                   return new ClaseService($app->make(ClaseRepositoryInterface::class));
               });

               $this->app->singleton(EstudianteService::class, function ($app) {
                   return new EstudianteService($app->make(EstudianteRepositoryInterface::class));
               });

               $this->app->singleton(ReprogramacionService::class, function ($app) {
                   return new ReprogramacionService(
                       $app->make(ReprogramacionRepositoryInterface::class),
                       $app->make(JustificacionRepositoryInterface::class)
                   );
               });

               $this->app->singleton(ReportService::class, function ($app) {
                   return new ReportService(
                       $app->make(JustificacionRepositoryInterface::class),
                       $app->make(ClaseRepositoryInterface::class)
                   );
               });

        // Registrar el NotificationGateway
        $this->app->singleton(NotificationGatewayInterface::class, function ($app) {
            return new NotificationGatewayImpl();
        });

        // Registrar la implementación concreta del notificador (usa el Gateway)
        $this->app->singleton(JustificationStatusNotifier::class, function ($app) {
            return new JustificationStatusNotifierImpl(
                $app->make(NotificationGatewayInterface::class)
            );
        });

        // Registrar el sujeto observable
        $this->app->singleton(JustificationSubject::class, function ($app) {
            return new JustificationDecisionSubject();
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

