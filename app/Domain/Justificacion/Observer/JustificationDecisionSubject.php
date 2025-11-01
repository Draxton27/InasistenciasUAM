<?php

namespace App\Domain\Justificacion\Observer;

use SplObjectStorage;
use App\Models\Justificacion;
use App\Models\User;
use App\Domain\Justificacion\Observer\Contracts\JustificationObserver;
use App\Domain\Justificacion\Observer\Contracts\JustificationSubject;

class JustificationDecisionSubject implements JustificationSubject
{
    private SplObjectStorage $observers;

    public function __construct()
    {
        $this->observers = new SplObjectStorage();
    }

    public function attach(JustificationObserver $observer): void
    {
        $this->observers->attach($observer);
    }

    public function detach(JustificationObserver $observer): void
    {
        $this->observers->detach($observer);
    }

    public function notify(
        Justificacion $justificacion,
        string $estado,
        ?User $actor = null,
        ?string $motivo = null
    ): void {
        foreach ($this->observers as $observer) {
            $observer->update($justificacion, $estado, $actor, $motivo);
        }
    }
}
