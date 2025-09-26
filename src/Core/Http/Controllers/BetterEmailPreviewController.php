<?php

namespace Basement\BetterMails\Core\Http\Controllers;

use Basement\BetterMails\Core\Models\BetterEmail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\ResponseFactory;

final class BetterEmailPreviewController
{
    public function __invoke(Request $request): ResponseFactory|Response
    {
        $mail = BetterEmail::query()->find($request->mail);

        return response($mail->html);
    }
}
