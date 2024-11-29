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