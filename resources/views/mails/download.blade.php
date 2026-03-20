@php
    $panelId = Filament\Facades\Filament::getCurrentPanel()->getId();
    $tenant = Filament\Facades\Filament::getTenant();
    $routeParams = array_filter([
        'tenant' => $tenant?->getRouteKey(),
        'mail' => $getState()->mail_id,
        'attachment' => $getState()->id,
        'filename' => $getState()->filename,
    ]);
@endphp
<a type="button"
    href="{{ route('filament.' . $panelId . '.mails.attachment.download', $routeParams) }}"
    class="rounded-md bg-white dark:bg-gray-700 px-3.5 py-2.5 text-sm font-semibold cursor-pointer text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">Download</a>
