<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RejectAdminFromUserBooks
{
    /**
     * Admin tidak memiliki rak/koleksi pribadi seperti pengguna biasa.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->isAdmin()) {
            return redirect()
                ->route('admin.dashboard')
                ->with('info', 'Akun admin tidak memiliki koleksi buku pengguna.');
        }

        return $next($request);
    }
}
