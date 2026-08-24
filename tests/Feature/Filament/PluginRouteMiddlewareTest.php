<?php

namespace Basement\BetterMails\Tests\Feature\Filament;

use Basement\BetterMails\Tests\Fixtures\FIlament\PluginPanelProvider;
use Basement\BetterMails\Tests\Fixtures\Http\TeapotMiddleware;
use Basement\BetterMails\Tests\TestCase;
use Illuminate\Support\Facades\Route;

class PluginRouteMiddlewareTest extends TestCase
{
    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('filament-better-mails.routes.middleware', [TeapotMiddleware::class]);
    }

    public function test_configured_middleware_is_applied_to_the_plugin_routes(): void
    {
        $routeNames = [
            'filament.plugin-test.mails.preview',
            'filament.plugin-test.mails.attachment.download',
        ];

        foreach ($routeNames as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);

            $this->assertNotNull($route);
            $this->assertContains(TeapotMiddleware::class, $route->gatherMiddleware());
        }
    }

    public function test_requests_run_through_the_configured_middleware(): void
    {
        $this->get('/plugin-test/mails/1/preview')->assertStatus(418);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ...parent::getPackageProviders($app),
            PluginPanelProvider::class,
        ];
    }
}
