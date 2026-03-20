@php
    $panelId = Filament\Facades\Filament::getCurrentPanel()->getId();
    $tenant = Filament\Facades\Filament::getTenant();
    $routeParams = array_filter([
        'tenant' => $tenant?->getRouteKey(),
        'mail' => $mail->id,
    ]);
@endphp
<div class="w-full h-screen">
    <iframe
        src="{{ route('filament.' . $panelId . '.mails.preview', $routeParams) }}"
        class="w-full h-full max-w-full" style="width: 100vw; height: 100vh; border: none;">
    </iframe>
</div>
