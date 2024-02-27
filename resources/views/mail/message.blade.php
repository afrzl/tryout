<x-mail::message>
# {{ $data->title }}
Pengirim: {{ $data->name }} - {{ $data->email }}

{!! $data->message !!}

<br>
{{ config('app.name') }}
</x-mail::message>
