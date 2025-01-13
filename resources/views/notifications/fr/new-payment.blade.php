@component('mail::message')
@include('notifications.header')

<h1 class="name">Bonjour {{ $notifiable->lastname }},</h1>
@if ($notifiable->role_id == 2)
<div class="main-content">
  Nous sommes ravis de vous confirmer que votre paiement pour la
  réservation <span class="bold">{{ $reservation->id }}</span> a été effectué avec succès.

  Cependant, nous vous rappelons que pour confirmer votre réservation,
  il est nécessaire de payer au moins <span class="bold">50%</span> du montant total.
  Si vous n'avez pas encore effectué ce paiement, nous vous invitons à le compléter dès que possible
  pour éviter que la ressource sollicitée ne soit attribuée à quelqu'un d'autre.

  Voici les détails de votre réservation :
  <ul>
    <li><span class="bold">Numéro de réservation :</span> {{ $reservation->id }}</li>
    <li><span class="bold">Date de début :</span> {{ $reservation->start_date }}</li>
    <li><span class="bold">Date de fin :</span> {{ $reservation->end_date }}</li>
    <li><span class="bold">Heure de début :</span> {{ $reservation->start_hour }} (GMT+1)</li>
    <li><span class="bold">Heure de fin :</span> {{ $reservation->end_hour }} (GMT+1)</li>
    <li><span class="bold">Ressource sollicitée :</span> {{ $reservation->ressource->space->name }}</li>
    <li><span class="bold">Agence :</span> {{ $reservation->ressource->agency->name }}</li>
    <li><span class="bold">Montant total :</span> {{ $reservation->initial_amount }} FCFA</li>
    <li><span class="bold">Montant payé :</span> {{ $payment->amount }} FCFA</li>
    <li><span class="bold">Montant restant à payer :</span> {{ $reservation->amount_due }} FCFA</li>
    <li><span class="bold">Paiement fait auprès de :</span> {{ $payment->processedBy->lastname }} {{ $payment->processedBy->firstname }}</li>
  </ul>

  Nous vous remercions de votre attention et nous nous réjouissons de vous accueillir
  dans notre établissement.

  Cordialement,
</div>
@else
<div class="main-content">
  Nous vous informons que le paiement pour la
  réservation <span class="bold">{{ $reservation->id }}</span> a été effectué avec succès.

  Cependant, nous vous rappelons que pour confirmer la réservation,
  il est nécessaire de payer au moins <span class="bold">50%</span> du montant total.
  Si le client n'a pas encore effectué ce paiement,
  nous vous invitons à le contacter pour le rappeler.

  Voici les détails de la réservation :

  <ul>
    <li><span class="bold">Numéro de réservation :</span> {{ $reservation->id }}</li>
    <li><span class="bold">Date de début :</span> {{ $reservation->start_date }}</li>
    <li><span class="bold">Date de fin :</span> {{ $reservation->end_date }}</li>
    <li><span class="bold">Heure de début :</span> {{ $reservation->start_hour }} (GMT+1)</li>
    <li><span class="bold">Heure de fin :</span> {{ $reservation->end_hour }} (GMT+1)</li>
    <li><span class="bold">Ressource sollicitée :</span> {{ $reservation->ressource->space->name }}</li>
    <li><span class="bold">Client :</span> {{ $reservation->client->lastname }} {{ $reservation->client->firstname }}</li>
    <li><span class="bold">Agence :</span> {{ $reservation->ressource->agency->name }}</li>
    <li><span class="bold">Montant total :</span> {{ $reservation->initial_amount }} FCFA</li>
    <li><span class="bold">Montant payé :</span> {{ $payment->amount }} FCFA</li>
    <li><span class="bold">Montant restant à payer :</span> {{ $reservation->amount_due }} FCFA</li>
    <li><span class="bold">Paiement fait auprès de :</span> {{ $payment->processedBy->lastname }} {{ $payment->processedBy->firstname }}</li>
  </ul>

  Pour consulter la réservation, vous pouvez utiliser le lien suivant :
  <a href="{{ url($admin_url) }}" class="link">voir la réservation</a>

  Nous vous remercions de votre attention et nous vous invitons à vérifier les
  détails de la réservation.

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