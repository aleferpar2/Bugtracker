<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\EnsureEmailIsVerified as Middleware;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return mixed
     */
    public function handle($request, Closure $next, $redirectToRoute = null): Response
    {
        if (! $request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
            ! $request->user()->hasVerifiedEmail())) {
            return $request->expectsJson()
                ? abort(403, 'Tu dirección de correo electrónico no está verificada.')
                : redirect()->route($redirectToRoute ?: 'verification.notice')
                    ->with('warning', 'Por favor, verifica tu dirección de correo electrónico.');
        }

        return $next($request);
    }
} 