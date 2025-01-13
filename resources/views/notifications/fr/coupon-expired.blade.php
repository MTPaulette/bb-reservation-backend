@component('mail::message')
@include('notifications.header')

<h1 class="name">Bonjour {{ $notifiable->lastname }},</h1>
<div class="main-content">
  Nous vous rappelons que le coupon de réduction {{ $coupon->name }} a expiré le {{ $coupon->expired_on }}.

  Voici les détails de votre coupon :
  <ul>
    <li><span class="bold">Nom de coupon :</span> {{ $coupon->name }}</li>
    <li><span class="bold">Code de coupon :</span> {{ $coupon->code }}</li>
    <li><span class="bold">Valeur de la réduction :</span> {{ $coupon->percent ? $coupon->percent.' %' : ''}} {{ $coupon->amount ? $coupon->amount.' FCFA' : '' }}</li>
    <li><span class="bold">Nombre total d'utilisation :</span> {{ $coupon->total_usage }}</li>
    <li><span class="bold">Date d'expiration :</span> {{ $coupon->expired_on }}</li>
  </ul>

  Nous regrettons que vous n'ayez pas pu utiliser ce coupon avant son 
  expiration. Si vous souhaitez continuer à bénéficier de réductions et 
  d'offres spéciales, nous vous invitons à vous inscrire à notre 
  newsletter ou à suivre nos réseaux sociaux.

  Nous vous remercions de votre fidélité et nous nous réjouissons de 
  vous accueillir dans notre établissement.

  Cordialement,
</div>
<h2 style="color:black;">
  La Direction.
</h2>
@include('notifications.footer')
@endcomponent

<style>
  .name {
    color:black;
  }
  .main-content {
    font-weight: 400;
    color:#111;
  }
  .bold {
    font-weight: 600;
  }
  .link {
    font-weight: 600;
    color: #0b9444;
  }
  .ps {
    font-weight: 600;
    font-size: 12px;
    color: red;
  }
</style>