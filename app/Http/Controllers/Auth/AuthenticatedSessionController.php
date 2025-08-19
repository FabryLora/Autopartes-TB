<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request)
    {

        return redirect('/');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario' => 'required', // Campo que puede ser name o email
            'password' => 'required',
        ]);

        $login = $request->input('usuario');
        $password = $request->input('password');

        // Determinar si es email o name
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $credentials = [
            $fieldType => $login,
            'password' => $password,
            'autorizado' => true, // Solo usuarios autorizados pueden entrar
        ];

        if (Auth::guard()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/privada/productos');
        }

        return redirect("/")->withErrors("Las credenciales proporcionadas no son correctas o la cuenta no está autorizada.");
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        session()->forget('cliente_seleccionado');
        Auth::guard('web')->logout();

        return Inertia::location('/');
    }
}
