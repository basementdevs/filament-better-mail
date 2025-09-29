<?php

namespace Basement\BetterMails\Tests;

use Basement\BetterMails\FilamentBetterMailsServiceProvider;
use Basement\BetterMails\Tests\Fixtures\FIlament\AdminPanelProvider;
use Filament\FilamentServiceProvider;
use Filament\Support\SupportServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Filesystem\Filesystem;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Basement\\BetterMails\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
        $this->loadMigrations();
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
         foreach (\Illuminate\Support\Facades\File::allFiles(__DIR__ . '/database/migrations') as $migration) {
            (include $migration->getRealPath())->up();
         }
         */
    }

    public function loadMigrations(): void
    {
        $filesystem = new Filesystem;
        $migrationFiles = $filesystem->files(__DIR__.'/../database/migrations/');

        // Sorting to ensure migrations run in the correct order
        usort($migrationFiles, function ($a, $b) {
            return strcmp($a->getFilename(), $b->getFilename());
        });

        foreach ($migrationFiles as $file) {
            // Skip if not a stub file
            if ($file->getExtension() !== 'stub') {
                continue;
            }

            $migration = include $file->getPathname();
            $migration->up();
        }
    }

    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            SupportServiceProvider::class,
            FilamentBetterMailsServiceProvider::class,
            AdminPanelProvider::class,
        ];
    }
}
