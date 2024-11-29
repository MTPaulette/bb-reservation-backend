
@component('mail::message')
# Invoice Paid
 
Your invoice has been paid!
 
@component('mail::button', ['url' => $url])
View Invoice
@endcomponent
 
Thanks,<br>
{{ config('app.name') }}
@endcomponent



<x-mail::message>
<h1>Bonjour {{ $name }} !</h1>
<span>{{ $message }}</span>

<x-mail::button :url="''">
Button Text
</x-mail::button>

<!-- Thanks,<br>
{{ config('app.name') }} -->

@include('notifications.footer')

</x-mail::message>

@component('mail::message')
# Invoice Paid

<h1>Bonjour {{ $name }} !</h1>
<span>{{ $message }}</span>

Your invoice has been paid!
 
@component('mail::button', ['url' => $url, 'color' => 'green'])
{{ $action_name }}
@endcomponent
 
@component('mail::panel')
This is the panel content.
@endcomponent

@component('mail::table')
| Laravel       | Table         | Example  |
| ------------- |:-------------:| --------:|
| Col 2 is      | {{ $name }}   | $10      |
| Col 3 is      | Right-Aligned | $20      |
@endcomponent


Thanks,<br>
<!-- {{ config('app.name') }} -->

@include('notifications.footer')
@endcomponent