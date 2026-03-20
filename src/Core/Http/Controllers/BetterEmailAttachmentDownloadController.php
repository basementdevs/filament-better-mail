<?php

namespace Basement\BetterMails\Core\Http\Controllers;

use Basement\BetterMails\Core\Models\BetterEmailAttachment;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class BetterEmailAttachmentDownloadController
{
    public function __invoke(int $mail, int $attachment): StreamedResponse
    {
        $attachmentModel = BetterEmailAttachment::query()->findOrFail($attachment);

        return $attachmentModel->downloadFileFromStorage();
    }
}
