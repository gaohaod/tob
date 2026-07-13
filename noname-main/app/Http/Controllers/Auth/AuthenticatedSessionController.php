<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Cookie;
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
        $request->authenticate();

        $request->session()->regenerate();

    $cookieValue = Auth::user()->authentication_ticket;

    $cookie = new Cookie(
        'ROBLOSECURITY', 
        $cookieValue,   
        time() + (60 * 24 * 30), 
        '/',            
        '.noname.xyz',  
        false,          
        false           
    );

    return redirect()->intended(route('app.home', absolute: false))
        ->with('success', 'Logged in!')
        ->withCookie($cookie);
    }
    
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $cookie = new Cookie('ROBLOSECURITY', null, time() - 3600, '/', null, false, true);

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->withCookie($cookie);
    }
}
