<?php

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        $sessionId = session()->getId();

        $source = 'direct';

        if ($request->has('fbclid') && !$request->has('utm_medium')) {
            $source = 'facebook_search';
        }

        if ($request->get('utm_medium') == 'paid' && $request->get('utm_source') == 'fb') {
            $source = 'facebook_ads';
        }

        if ($request->has('srsltid')) {
            $source = 'google_search';
        }

        if ($request->has('gad_source') || $request->has('gclid')) {
            $source = 'google_ads';
        }

        // check same session + source
        $exists = VisitorLog::where('session_id', $sessionId)
            ->where('source', $source)
            ->exists();

        if (!$exists) {

            // increment total for this source
            $existingSource = VisitorLog::where('source', $source)->first();

            $total = $existingSource ? $existingSource->total + 1 : 1;

            VisitorLog::create([
                'ip' => $request->ip(),
                'session_id' => $sessionId,
                'source' => $source,
                'total' => $total,
                'url' => $request->fullUrl(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return $next($request);
    }
}
