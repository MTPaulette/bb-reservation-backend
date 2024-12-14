@component('mail::message')

<h1 class="name">Hello {{ $notifiable->lastname }},</h1>
@if ($notifiable->role_id == 2)
<div class="main-content">
  We are delighted to confirm that your reservation for 
  <span class="bold">{{ $reservation->ressource->space->name }} </span>has been completed successfully.

  Here are the details of your reservation:<ul>
    <li><span class="bold">Reservation number:</span> {{ $reservation->id }}</li>
    <li><span class="bold">Start date:</span> {{ $reservation->start_date }}</li>
    <li><span class="bold">End date:</span> {{ $reservation->end_date }}</li>
    <li><span class="bold">Start time:</span> {{ $reservation->start_hour }} (GMT+1)</li>
    <li><span class="bold">End time:</span> {{ $reservation->end_hour }} (GMT+1)</li>
    <li><span class="bold">Requested resource:</span> {{ $reservation->ressource->space->name }}</li>
    <li><span class="bold">Agency:</span> {{ $reservation->ressource->agency->name }}</li>
    <li><span class="bold">Coupon:</span>
    @isset ($reservation->coupon)
      {{ $reservation->coupon->name }} | {{ $reservation->coupon->code }}
    @else
      No coupons
    @endisset</li>
    @isset ($reservation->coupon)
      <li>
        <span class="bold">Coupon value:</span>
        @if ($reservation->coupon->percent)
          {{ $reservation->coupon->percent }} %
        @endif
        @if ($reservation->coupon->amount)
          {{ $reservation->coupon->amount }} FCFA
        @endif
      </li>
    @endisset
    <li><span class="bold">Total amount:</span> {{ $reservation->initial_amount }} FCFA</li>
    <li><span class="bold">Amount remaining to be paid:</span> {{ $reservation->amount_due }} FCFA</li>
    <li><span class="bold">Reservation made by :</span> {{ $reservation->createdBy->lastname }} {{ $reservation->createdBy->firstname }}</li>
  </ul>
  
  However, we inform you that despite the reservation,
  there is no guarantee that the resource will be allocated to you.
  To remedy this, it is necessary to pay at least <span class="bold">50%</span> of the total reservation amount.
  The remaining <span class="bold">50%</span> must be paid before the reservation start time.

  We invite you to make payment as soon as possible to ensure availability of the resource.
  You can use the following link to make payment: <a href="{{ url($client_url) }}" class="link">see reservation</a>

  We thank you for your attention and we look forward to welcoming you to our establishment.

  Sincerely,
</div>
@else
<div class="main-content">
  We inform you that the reservation for
  <span class="bold">{{ $reservation->ressource->space->name }} </span>has been completed successfully.

  Here are the details of your reservation:
  <ul>
    <li><span class="bold">Reservation number:</span> {{ $reservation->id }}</li>
    <li><span class="bold">Start date:</span> {{ $reservation->start_date }}</li>
    <li><span class="bold">End date:</span> {{ $reservation->end_date }}</li>
    <li><span class="bold">Start time:</span> {{ $reservation->start_hour }} (GMT+1)</li>
    <li><span class="bold">End time:</span> {{ $reservation->end_hour }} (GMT+1)</li>
    <li><span class="bold">Requested resource:</span> {{ $reservation->ressource->space->name }}</li>
    <li><span class="bold">Client :</span> {{ $reservation->client->lastname }} {{ $reservation->client->firstname }}</li>
    <li><span class="bold">Agency:</span> {{ $reservation->ressource->agency->name }}</li>
    <li><span class="bold">Coupon:</span>
    @isset ($reservation->coupon)
      {{ $reservation->coupon->name }} | {{ $reservation->coupon->code }}
    @else
      No coupons
    @endisset
    </li>
    @isset ($reservation->coupon)
      <li>
        <span class="bold">Coupon value:</span>
        @if ($reservation->coupon->percent)
          {{ $reservation->coupon->percent }} %
        @endif
        @if ($reservation->coupon->amount)
          {{ $reservation->coupon->amount }} FCFA
        @endif
      </li>
    @endisset
    <li><span class="bold">Total amount:</span> {{ $reservation->initial_amount }} FCFA</li>
    <li><span class="bold">Amount remaining to be paid:</span> {{ $reservation->amount_due }} FCFA</li>
    <li><span class="bold">Reservation made by :</span> {{ $reservation->createdBy->lastname }} {{ $reservation->createdBy->firstname }}</li>
  </ul>

  We remind you that the customer must pay at least <span class="bold">50%</span> of the total amount of the reservation
  to guarantee the availability of the resource. The rest of the <span class="bold">50%</span> must be paid
  before the start time of the reservation.

  To view the reservation, you can use the following link:
  <a href="{{ url($admin_url) }}" class="link">see reservation</a>

  We invite you to follow the status of the reservation and contact the customer if necessary.

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