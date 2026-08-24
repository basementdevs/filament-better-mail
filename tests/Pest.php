<?php

use Basement\BetterMails\Tests\PluginPanelTestCase;
use Basement\BetterMails\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

pest()->extend(PluginPanelTestCase::class)
    ->in('Panel');
