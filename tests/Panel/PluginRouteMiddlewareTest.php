<?php

use Basement\BetterMails\Tests\Fixtures\Http\DenyMiddleware;
use Basement\BetterMails\Tests\Fixtures\Http\TeapotMiddleware;
use Illuminate\Support\Facades\Route;

dataset('plugin routes', [
    'preview' => 'filament.plugin-test.mails.preview',
    'attachment download' => 'filament.plugin-test.mails.attachment.download',
]);

it('should apply the configured middleware to the plugin routes', function (string $routeName): void {
    $route = Route::getRoutes()->getByName($routeName);

    expect($route)->not->toBeNull()
        ->and($route->gatherMiddleware())->toContain(TeapotMiddleware::class);
})->with('plugin routes');

it('should apply the panel auth middleware to the plugin routes', function (string $routeName): void {
    $route = Route::getRoutes()->getByName($routeName);

    expect($route)->not->toBeNull()
        ->and($route->gatherMiddleware())->toContain(DenyMiddleware::class);
})->with('plugin routes');

it('should block unauthenticated requests', function (): void {
    $this->get('/plugin-test/mails/1/preview')->assertStatus(401);
    $this->get('/plugin-test/mails/1/attachments/1/download/file.txt')->assertStatus(401);
});
