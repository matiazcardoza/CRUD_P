<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autenticar al usuario
        $request->authenticate();

        // Regenerar la sesión para evitar fijación de sesión
        $request->session()->regenerate();

        // Verificar si el usuario está activo
        if (!auth()->user()->activo) {
            auth()->logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta está inactiva.',
            ]);
        }

        // Redirigir según el rol
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('dashboard');
        }

        if (auth()->user()->hasRole('user')) {
            return redirect()->route('home');
        }

        // Redirigir al home si no se reconoce el rol (puedes ajustar esto según tus necesidades)
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Cerrar sesión del usuario
        Auth::guard('web')->logout();

        // Invalidar la sesión y regenerar el token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirigir al inicio
        return redirect('/');
    }
}
