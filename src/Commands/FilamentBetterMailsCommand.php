<?php

namespace Basement\BetterMails\Commands;

use Illuminate\Console\Command;

class FilamentBetterMailsCommand extends Command
{
    public $signature = 'filament-better-mails';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
