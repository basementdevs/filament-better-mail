<?php

namespace Basement\BetterMails\Tests\Fixtures\Http;

use Closure;
use Illuminate\Http\Request;

class DenyMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        abort(401);
    }
}
