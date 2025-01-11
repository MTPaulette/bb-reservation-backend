@component('mail::message')
<h1 class="name">Hello {{ $notifiable->lastname }},</h1>
@if ($notifiable->role_id == 2)
<div class="main-content">
  We are pleased to confirm that your payment for the
  reservation <span class="bold">{{ $reservation->id }}</span> was completed successfully.

  However, we remind you that to confirm your reservation,
  it is necessary to pay at least <span class="bold">50%</span> of the total amount.
  If you have not yet made this payment, we invite you to complete it as soon as possible
  to prevent the requested resource from being allocated to someone else.

  Here are the details of your reservation:
  <ul>
    <li><span class="bold">Reservation number:</span> {{ $reservation->id }}</li>
    <li><span class="bold">Start date:</span> {{ $reservation->start_date }}</li>
    <li><span class="bold">End date:</span> {{ $reservation->end_date }}</li>
    <li><span class="bold">Start time:</span> {{ $reservation->start_hour }} (GMT+1)</li>
    <li><span class="bold">End time:</span> {{ $reservation->end_hour }} (GMT+1)</li>
    <li><span class="bold">Requested resource:</span> {{ $reservation->ressource->space->name }}</li>
    <li><span class="bold">Agency:</span> {{ $reservation->ressource->agency->name }}</li>
    <li><span class="bold">Total amount:</span> {{ $reservation->initial_amount }} FCFA</li>
    <li><span class="bold">Amount paid:</span> {{ $payment->amount }} FCFA</li>
    <li><span class="bold">Amount remaining to be paid:</span> {{ $reservation->amount_due }} FCFA</li>
    <li><span class="bold">Payment made to:</span> {{ $payment->processedBy->lastname }} {{ $payment->processedBy->firstname }}</li>
  </ul>

  We thank you for your attention and we look forward to welcoming you
  in our establishment.

  Sincerely,
</div>
@else
<div class="main-content">
  We inform you that payment for the
  reservation <span class="bold">{{ $reservation->id }}</span> was completed successfully.

  However, we remind you that to confirm the reservation,
  it is necessary to pay at least <span class="bold">50%</span> of the total amount.
  If the customer has not yet made this payment,
  we invite you to contact him to call him back.

  Here are the reservation details:

  <ul>
    <li><span class="bold">Reservation number:</span> {{ $reservation->id }}</li>
    <li><span class="bold">Start date:</span> {{ $reservation->start_date }}</li>
    <li><span class="bold">End date:</span> {{ $reservation->end_date }}</li>
    <li><span class="bold">Start time:</span> {{ $reservation->start_hour }} (GMT+1)</li>
    <li><span class="bold">End time:</span> {{ $reservation->end_hour }} (GMT+1)</li>
    <li><span class="bold">Requested resource:</span> {{ $reservation->ressource->space->name }}</li>
    <li><span class="bold">Client :</span> {{ $reservation->client->lastname }} {{ $reservation->client->firstname }}</li>
    <li><span class="bold">Agency:</span> {{ $reservation->ressource->agency->name }}</li>
    <li><span class="bold">Total amount:</span> {{ $reservation->initial_amount }} FCFA</li>
    <li><span class="bold">Amount paid:</span> {{ $payment->amount }} FCFA</li>
    <li><span class="bold">Amount remaining to be paid:</span> {{ $reservation->amount_due }} FCFA</li>
    <li><span class="bold">Payment made to:</span> {{ $payment->processedBy->lastname }} {{ $payment->processedBy->firstname }}</li>
  </ul>

  To view the reservation, you can use the following link:
  <a href="{{ url($admin_url) }}" class="link">see reservation</a>

  We thank you for your attention and invite you to check the
  reservation details.

  Sincerely,
</div>
@endif
<h2 style="color:black;">
  The Management.
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