<?php

namespace ZablockiBros\Jetpack\Http\Middleware;

use ZablockiBros\Jetpack\Events\Running;

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
        Running::dispatch($request);

        return $next($request);
    }
}
