<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RevalidateBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
{
    $response = $next($request);

    // Cek jika response merupakan instance dari BinaryFileResponse
    if ($response instanceof \Symfony\Component\HttpFoundation\BinaryFileResponse) {
        // Menetapkan header untuk BinaryFileResponse
        $response->headers->set('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    } else {
        // Untuk response biasa, gunakan method header()
        $response->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate')
                 ->header('Pragma', 'no-cache')
                 ->header('Expires', 'Fri, 01 Jan 1990 00:00:00 GMT');
    }

    return $response;
}

}
