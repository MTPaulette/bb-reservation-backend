@component('mail::message')

<h1 class="name">Bonjour {{ $notifiable->lastname }},</h1>
@if ($notifiable->role_id == 2)
<div class="main-content">
  Nous sommes ravis de vous confirmer que votre réservation pour 
  <span class="bold">{{ $reservation->ressource->space->name }} </span>a été effectuée avec succès.

  Voici les détails de votre réservation :
  <ul>
    <li><span class="bold">Numéro de réservation :</span> {{ $reservation->id }}</li>
    <li><span class="bold">Date de début :</span> {{ $reservation->start_date }}</li>
    <li><span class="bold">Date de fin :</span> {{ $reservation->end_date }}</li>
    <li><span class="bold">Heure de début :</span> {{ $reservation->start_hour }} (GMT+1)</li>
    <li><span class="bold">Heure de fin :</span> {{ $reservation->end_hour }} (GMT+1)</li>
    <li><span class="bold">Ressource sollicitée :</span> {{ $reservation->ressource->space->name }}</li>
    <li><span class="bold">Agence :</span> {{ $reservation->ressource->agency->name }}</li>
    <li><span class="bold">Coupon :</span>
    @isset ($reservation->coupon)
      {{ $reservation->coupon->name }} | {{ $reservation->coupon->code }}
    @else
      Aucun coupon
    @endisset
    </li>
    @isset ($reservation->coupon)
      <li>
        <span class="bold">Valeur du coupon :</span>
        @if ($reservation->coupon->percent)
          {{ $reservation->coupon->percent }} %
        @endif
        @if ($reservation->coupon->amount)
          {{ $reservation->coupon->amount }} FCFA
        @endif
      </li>
    @endisset
    <li><span class="bold">Montant total :</span> {{ $reservation->initial_amount }} FCFA</li>
    <li><span class="bold">Montant restant à payer :</span> {{ $reservation->amount_due }} FCFA</li>
  </ul> 

  Cependant, nous vous informons que malgré la réservation,
  rien ne garantit que la ressource vous soit attribuée.
  Pour y remédier, il est nécessaire de payer au moins <span class="bold">50%</span> du montant total de la réservation.
  Le reste des <span class="bold">50%</span> devra être payé avant l'heure de début de la réservation.

  Nous vous invitons à effectuer le paiement dès que possible pour garantir la disponibilité de la ressource.
  Vous pouvez utiliser le lien suivant pour effectuer le paiement : <a href="{{ url($client_url) }}" class="link">voir la reservation</a>

  Nous vous remercions de votre attention et nous nous réjouissons de vous accueillir dans notre établissement.

  Cordialement,
</div>
@else
<div class="main-content">
  Nous vous informons que la réservation pour
  <span class="bold">{{ $reservation->ressource->space->name }} </span>a été effectuée avec succès.

  Voici les détails de votre réservation :
  <ul>
    <li><span class="bold">Numéro de réservation :</span> {{ $reservation->id }}</li>
    <li><span class="bold">Date de début :</span> {{ $reservation->start_date }}</li>
    <li><span class="bold">Date de fin :</span> {{ $reservation->end_date }}</li>
    <li><span class="bold">Heure de début :</span> {{ $reservation->start_hour }} (GMT+1)</li>
    <li><span class="bold">Heure de fin :</span> {{ $reservation->end_hour }} (GMT+1)</li>
    <li><span class="bold">Ressource sollicitée :</span> {{ $reservation->ressource->space->name }}</li>
    <li><span class="bold">Agence :</span> {{ $reservation->ressource->agency->name }}</li>
    <li><span class="bold">Coupon :</span>
    @isset ($reservation->coupon)
      {{ $reservation->coupon->name }} | {{ $reservation->coupon->code }}
    @else
      Aucun coupon
    @endisset
    </li>
    @isset ($reservation->coupon)
      <li>
        <span class="bold">Valeur du coupon :</span>
        @if ($reservation->coupon->percent)
          {{ $reservation->coupon->percent }} %
        @endif
        @if ($reservation->coupon->amount)
          {{ $reservation->coupon->amount }} FCFA
        @endif
      </li>
    @endisset
    <li><span class="bold">Montant total :</span> {{ $reservation->initial_amount }} FCFA</li>
    <li><span class="bold">Montant restant à payer :</span> {{ $reservation->amount_due }} FCFA</li>
  </ul> 

  Nous vous rappelons que le client doit payer au moins <span class="bold">50%</span> du montant total de la réservation
  pour garantir la disponibilité de la ressource. Le reste des <span class="bold">50%</span> devra être payé
  avant l'heure de début de la réservation.

  Nous vous invitons à suivre l'état de la réservation et à contacter le client si nécessaire.

  Cordialement,
</div>
@endif
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
</style>