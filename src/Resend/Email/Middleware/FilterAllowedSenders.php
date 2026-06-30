<?php

namespace Basement\BetterMails\Resend\Email\Middleware;

use Basement\BetterMails\Core\Contracts\BetterMiddlewareContract;
use Basement\BetterMails\Core\Support\BetterMailLogger;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

final class FilterAllowedSenders implements BetterMiddlewareContract
{
    public function handle(Request $request, Closure $next): mixed
    {
        $allowed = config('filament-better-mails.webhooks.allowed_senders', []);

        if (empty($allowed)) {
            return $next($request);
        }

        $email = $this->extractEmail((string) $request->input('data.from', ''));
        $domain = Str::after($email, '@');

        $matches = collect($allowed)->contains(function ($value) use ($email, $domain): bool {
            $value = strtolower(trim((string) $value));

            return str_contains($value, '@')
                ? $value === $email
                : $value !== '' && $value === $domain;
        });

        if (! $matches) {
            BetterMailLogger::info('Webhook ignored: sender not in allowed list.', [
                'type' => $request->input('type'),
                'from' => $request->input('data.from'),
            ]);

            return response()->json(['message' => 'Webhook ignored: sender not allowed.'], 200);
        }

        return $next($request);
    }

    /**
     * Extract the bare email address from a "Name <email@domain>" style string.
     */
    private function extractEmail(string $from): string
    {
        if (preg_match('/<([^>]+)>/', $from, $matches)) {
            $from = $matches[1];
        }

        return strtolower(trim($from));
    }
}
