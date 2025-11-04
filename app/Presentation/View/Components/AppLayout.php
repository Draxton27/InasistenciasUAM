<?php

namespace App\Presentation\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Component: AppLayout
 * Capa: Presentation
 * Componente de layout para usuarios autenticados
 */
class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}

