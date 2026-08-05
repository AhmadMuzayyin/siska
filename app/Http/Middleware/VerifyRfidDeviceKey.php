<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpFoundation\Response;

class VerifyRfidDeviceKey
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $configuredKey = (string) Config::get('services.rfid.device_key', '');
        $providedKey = (string) $request->header('X-Device-Key', '');

        if ($configuredKey === '' || ! hash_equals($configuredKey, $providedKey)) {
            abort(401, 'Invalid or missing device key.');
        }

        return $next($request);
    }
}
