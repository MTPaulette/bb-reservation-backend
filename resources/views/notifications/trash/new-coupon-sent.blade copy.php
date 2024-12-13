
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


============================================================================
@component('mail::message')

<h1>Nouveau coupon de réduction !!!</h1>
<h2>Bonjour {{ $name }},</h2>
<span>{{ $message }}</span>
 
<!-- @component('mail::button', ['url' => $url, 'color' => 'green'])
{{ $action_name }}
@endcomponent -->
<br />
<br />
<a href="{{ url($url) }}"
    style="
        text-decoration: none !important;
        padding: 10px 30px; background: #0b9444; border-radius: 20px;
    ">
  <span style="color: white; font-weight: 700px; margin-left: 5px;">RESERVEZ MAINTENANT</span>
</a>
@include('notifications.footer')
@endcomponent



========================================
@component('mail::message')

<h1>Nouveau coupon de réduction !!!</h1>
<h2>Bonjour {{ $name }},</h2>
<span>{{ $message }}</span>

@component('mail::panel')
    Code : {{ $coupon->code }}
    Montant : {{ $coupon->amount }}%
@endcomponent
 
<!-- @component('mail::button', ['url' => $url, 'color' => 'green'])
{{ $action_name }}
@endcomponent -->
<br />
<br />
<a href="{{ url($url) }}"
    style="
        text-decoration: none !important;
        padding: 10px 30px; background: #0b9444; border-radius: 20px;
    ">
  <span style="color: white; font-weight: 700px; margin-left: 5px;">RESERVEZ MAINTENANT</span>
</a>
@include('notifications.footer')
@endcomponent