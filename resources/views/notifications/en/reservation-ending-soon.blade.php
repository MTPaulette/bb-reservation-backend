@component('mail::message')

<h1 class="name">Hello {{ $notifiable->lastname }},</h1>
@if ($notifiable->role_id == 2)
<div class="main-content">
  We remind you that your reservation for 
  <span class="bold">{{ $reservation->ressource->space->name }} </span>ends in 30 minutes!

  Here are the details of your reservation:
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
  
  We invite you to prepare your belongings and leave the premises before the 
  end of reservation. 
  
  If you have any questions or concerns, please do not hesitate to contact us.

  Sincerely,
</div>
@else
<div class="main-content">
  We remind you that your reservation for 
  <span class="bold">{{ $reservation->ressource->space->name }} </span>ends in 30 minutes!

  Here are the details of your reservation:
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

  We invite you to check that the customer has left the premises before the end of the 
  reservation and to check the premises and equipment.

  Sincerely,
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