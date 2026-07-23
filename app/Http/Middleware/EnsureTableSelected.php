<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTableSelected
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah session 'table' ada dan memiliki 'id'
        if (! $request->session()->has('table.id')) {
            // Jika tidak ada session meja, kembalikan response/redirect
            return abort(403, 'Silakan scan QR Code di meja Anda terlebih dahulu untuk mengakses menu.');
        }

        return $next($request);
    }
}
