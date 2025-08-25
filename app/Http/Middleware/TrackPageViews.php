<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageViews
{
    public function handle(Request $request, Closure $next, string $pageName): Response
    {
        // Jangan catat kunjungan dari admin yang sedang login
        if (!auth()->check() || !auth()->user()->is_admin) {
            PageVisit::create([
                'page_name' => $pageName,
                'url'       => $request->path(),
                'ip_address'=> $request->ip(),
            ]);
        }

        return $next($request);
    }
}