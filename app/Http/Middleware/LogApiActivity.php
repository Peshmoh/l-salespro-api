<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ActivityLog;

class LogApiActivity
{
    public function handle($request, Closure $next)
    {
        // CONTINUE request first to grab response status
        $response = $next($request);

        ActivityLog::create([
            'user_id'     => optional($request->user())->id,
            'action'      => $request->method() . ' ' . $request->path(),
            'description' => json_encode([
                'status'   => $response->getStatusCode(),
                'payload'  => $request->except(['password', 'password_confirmation']),
            ]),
            'ip_address'  => $request->ip(),
        ]);

        return $response;
    }
}
