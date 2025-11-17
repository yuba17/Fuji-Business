<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario autenticado
     */
    public function show(): View
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    /**
     * Mostrar el perfil de otro usuario (para managers)
     */
    public function showUser(User $user): View
    {
        $this->authorize('view', $user);
        return view('profile.show', compact('user'));
    }
}
