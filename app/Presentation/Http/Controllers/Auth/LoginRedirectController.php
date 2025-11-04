<?php
namespace App\Presentation\Http\Controllers\Auth;

use App\Presentation\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginRedirectController extends Controller
{
    public function redirect()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'profesor') {
            return redirect()->route('profesor.dashboard');
        }

        return redirect()->route('justificaciones.index'); 
    }
}
