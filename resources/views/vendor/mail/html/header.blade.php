@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
@else
<img src="{{ asset('assets/media/logo.png') }}" class="logo" alt="{{ config('app.name') }}">
<!-- <img src="{{ asset('assets/media/logo.png') }}" class="logo" alt="{{ config('app.name') }}" width="180" style="display:block;margin:0 auto;max-width:180px;height:auto;"> -->
@endif
</a>
</td>
</tr>
