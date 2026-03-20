@component('mail::message')
# Better Mails — Attachment Test ✉

This is a **test email with an attachment** sent from the Filament Better Mails panel.

It verifies that your mailer is correctly configured, that emails
are being logged, and that attachments are stored on disk.

@component('mail::button', ['url' => $link])
Open Link
@endcomponent

A test attachment has been included with this email.

Thanks,
{{ config('app.name') }}
@endcomponent
