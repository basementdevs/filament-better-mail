<?php

namespace Basement\BetterMails\Tests;

use Basement\BetterMails\Tests\Fixtures\FIlament\PluginPanelProvider;
use Basement\BetterMails\Tests\Fixtures\Http\TeapotMiddleware;

class PluginPanelTestCase extends TestCase
{
    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('filament-better-mails.routes.middleware', [TeapotMiddleware::class]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ...parent::getPackageProviders($app),
            PluginPanelProvider::class,
        ];
    }
}
