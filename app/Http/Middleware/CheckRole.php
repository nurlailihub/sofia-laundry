<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user->role, $roles)) {
            if ($user->role === 'customer') {
                return redirect()->route('customer.dashboard');
            }

            if ($user->role === 'pimpinan') {
                return redirect()->route('admin.laporan.transaksi.index');
            }

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}
