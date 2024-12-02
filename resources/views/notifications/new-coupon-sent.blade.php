@component('mail::message')

<h1>Nouveau coupon de réduction !!!</h1>
<h2>Bonjour {{ $name }},</h2>
<span>
  Vous avez reçu un coupon de réduction ({{ $coupon->name }}).
  Il vous donne droit à une réduction de 
  {{ $coupon->percent ? $coupon->percent.' %' : ''}} {{ $coupon->amount ? $coupon->amount.' FCFA' : '' }}
  sur le montant de la réservation où vous l'utiliseriez.
  Il est utilisable {{ $coupon->total_usage }} fois et expire avant le {{ $coupon->expired_on }}.
  Vous pouvez utiliser dès présent
</span>

@component('mail::panel')
    Code : {{ $coupon->code }} <br/>
    Montant : {{ $coupon->percent ? $coupon->percent.' %' : ''}} {{ $coupon->amount ? $coupon->amount.' FCFA' : '' }}
@endcomponent

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