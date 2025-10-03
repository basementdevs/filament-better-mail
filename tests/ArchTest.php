<?php

arch('it will not use debugging functions')
    ->expect(['dd', 'dump', 'ray'])
    ->each->not->toBeUsed();

arch('Enums')
    ->expect('Basement\BetterMails\Core\Enums')
    ->toBeEnums();

arch('Enums should have Enum suffix')
    ->expect('Basement\BetterMails\Core\Enums')
    ->toBeEnums()
    ->toHaveSuffix('Enum');

arch('Listeners should have Listener suffix')
    ->expect('Basement\BetterMails\Core\Listeners')
    ->toBeClasses()
    ->toHaveSuffix('Listener');

arch('Actions should have Action suffix')
    ->expect('Basement\BetterMails\Core\Actions')
    ->toBeClasses()
    ->toHaveSuffix('Action');

arch('DTOs should have DTO suffix')
    ->expect('Basement\BetterMails\Core\DTOs')
    ->toBeClasses()
    ->toHaveSuffix('DTO');
