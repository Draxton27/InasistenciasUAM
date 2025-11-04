<?php

namespace App\Presentation\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Component: GuestLayout
 * Capa: Presentation
 * Componente de layout para usuarios invitados (login, registro)
 */
class GuestLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}

