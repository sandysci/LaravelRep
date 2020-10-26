<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ArcanedevLoggerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->query('token');
        $configToken = config('log-viewer.route.attributes.token');
        $decryptedToken = Crypt::decryptString($configToken);

        if (!is_null($token)) {
            session(['arcanedev_logger' => $token]);
        }

        if ($token === $decryptedToken || session('arcanedev_logger') === $decryptedToken) {
            return $next($request);
        }
        return ApiResponse::responseError(
            [
                'Device Info' => request()->header('User-Agent') ?? '',
                'Your IP' => request()->ip() ?? ''
            ],
            'Page Not Found. If error persists, contact developer@adasi.test',
            404
        );
    }
}
