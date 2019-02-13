<?php

namespace ZablockiBros\Jetpack\Http\Middleware;

use ZablockiBros\Jetpack\Events\Serving;

class DispatchRunningEvent
{
    /**
     * @param $request
     * @param $next
     *
     * @return mixed
     */
    public function handle($request, $next)
    {
        Serving::dispatch($request);

        return $next($request);
    }
}
