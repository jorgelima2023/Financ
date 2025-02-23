@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
{{ $slot }}
<img src="{{ asset('images/logo-define-100x100_v1.png') }}" class="logo" alt="Celke - Logo">
@endif
</a>
</td>
</tr>
