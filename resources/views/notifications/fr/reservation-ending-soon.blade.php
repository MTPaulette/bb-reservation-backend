@component('mail::message')

<h1 class="name">Bonjour {{ $notifiable->lastname }},</h1>
@if ($notifiable->role_id == 2)
<div class="main-content">
  Nous vous rappelons que votre réservation pour 
  <span class="bold">{{ $reservation->ressource->space->name }} </span>se termine dans 30 minutes ! 

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
    <li><span class="bold">Réservation faite par :</span> {{ $reservation->createdBy->lastname }} {{ $reservation->createdBy->firstname }}</li>
  </ul> 

  Nous vous invitons à préparer vos affaires et à quitter les lieux avant la 
  fin de la réservation. 
  
  Si vous avez des questions ou des préoccupations, n'hésitez pas à nous contacter.

  Cordialement,
</div>
@else
<div class="main-content">
  Nous vous rappelons que votre réservation pour 
  <span class="bold">{{ $reservation->ressource->space->name }} </span>se termine dans 30 minutes ! 

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
    <li><span class="bold">Réservation faite par :</span> {{ $reservation->createdBy->lastname }} {{ $reservation->createdBy->firstname }}</li>
  </ul> 

  Nous vous invitons à vérifier que le client a quitté les lieux avant la fin de la 
  réservation et à procéder à la vérification des lieux et des équipements.

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