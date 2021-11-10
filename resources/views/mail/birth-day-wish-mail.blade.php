@component('mail::message')
# Introduction

Happy Birthday <strong>{{ $name }} {{ $surname }}</strong>.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
