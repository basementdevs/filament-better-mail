<?php

namespace Basement\BetterMails\Tests\Feature\Filament;

use Basement\BetterMails\Tests\Fixtures\FIlament\PluginPanelProvider;
use Basement\BetterMails\Tests\Fixtures\Http\DenyMiddleware;
use Basement\BetterMails\Tests\Fixtures\Http\TeapotMiddleware;
use Basement\BetterMails\Tests\TestCase;
use Illuminate\Support\Facades\Route;

class PluginRouteMiddlewareTest extends TestCase
{
    private const ROUTE_NAMES = [
        'filament.plugin-test.mails.preview',
        'filament.plugin-test.mails.attachment.download',
    ];

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('filament-better-mails.routes.middleware', [TeapotMiddleware::class]);
    }

    public function test_configured_middleware_is_applied_to_the_plugin_routes(): void
    {
        foreach (self::ROUTE_NAMES as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);

            $this->assertNotNull($route);
            $this->assertContains(TeapotMiddleware::class, $route->gatherMiddleware());
        }
    }

    public function test_panel_auth_middleware_is_applied_to_the_plugin_routes(): void
    {
        foreach (self::ROUTE_NAMES as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);

            $this->assertNotNull($route);
            $this->assertContains(DenyMiddleware::class, $route->gatherMiddleware());
        }
    }

    public function test_unauthenticated_requests_are_blocked(): void
    {
        $this->get('/plugin-test/mails/1/preview')->assertStatus(401);
        $this->get('/plugin-test/mails/1/attachments/1/download/file.txt')->assertStatus(401);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ...parent::getPackageProviders($app),
            PluginPanelProvider::class,
        ];
    }
}
