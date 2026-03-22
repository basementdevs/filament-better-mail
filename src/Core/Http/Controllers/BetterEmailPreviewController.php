<?php

namespace Basement\BetterMails\Core\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;

final class BetterEmailPreviewController
{
    public function __invoke(Request $request): ResponseFactory|Response
    {
        $model = config('filament-better-mails.mails.models.mail');
        $mail = $model::query()->findOrFail($request->mail);

        return response($mail->html);
    }
}
