<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ViewModeController extends Controller
{
    /**
     * Establecer el modo de vista (individual / equipos) en sesión.
     */
    public function set(string $mode, Request $request): RedirectResponse
    {
        $allowed = ['individual', 'team'];

        if (! in_array($mode, $allowed, true)) {
            $mode = 'individual';
        }

        $request->session()->put('view_mode', $mode);

        // Redirigir a la opción por defecto según el modo seleccionado
        if ($mode === 'individual') {
            return redirect()->route('dashboard');
        } else {
            // Modo equipo: redirigir a "Mi equipo"
            return redirect()->route('teams.my');
        }
    }
}


