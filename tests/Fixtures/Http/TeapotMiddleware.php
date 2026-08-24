<?php

namespace Basement\BetterMails\Tests\Fixtures\Http;

use Closure;
use Illuminate\Http\Request;

class TeapotMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        abort(418);
    }
}
