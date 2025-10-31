<?php

namespace Basement\BetterMails\Core\Contracts;

use Closure;
use Illuminate\Http\Request;

interface BetterMiddlewareContract
{
    public function handle(Request $request, Closure $next): mixed;
}
