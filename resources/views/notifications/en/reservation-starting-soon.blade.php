@component('mail::message')
@include('notifications.header')

<h1 class="name">Hello {{ $notifiable->lastname }},</h1>
@if ($notifiable->role_id == 2)
<div class="main-content">
  We remind you that your reservation for
  <span class="bold">{{ $reservation->ressource->space->name }} </span>starts in 30 minutes! 

  Here are the details of your reservation:
  <ul>
    <li><span class="bold">Reservation number:</span> {{ $reservation->id }}</li>
    <li><span class="bold">Start date:</span> {{ $reservation->start_date }}</li>
    <li><span class="bold">End date:</span> {{ $reservation->end_date }}</li>
    <li><span class="bold">Start time:</span> {{ $reservation->start_hour }} (GMT+1)</li>
    <li><span class="bold">End time:</span> {{ $reservation->end_hour }} (GMT+1)</li>
    <li><span class="bold">Resource requested:</span> {{ $reservation->ressource->space->name }}</li>
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
    <li><span class="bold">Reservation made by:</span> {{ $reservation->createdBy->lastname }} {{ $reservation->createdBy->firstname }}</li>
  </ul>
  
  We remind you that if the payment is not effective or total, 
  you will need to pay before the start of the reservation time. 
  In addition, if the <span class="bold">50%</span> of the reservation has not been paid, 
  there is no guarantee that the resource will be available in the agency.
  
  We invite you to verify your payment and 
  contact us if you have any questions or concerns.

  Sincerely,
</div>
@else
<div class="main-content">
  We remind you that the reservation for
  <span class="bold">{{ $reservation->ressource->space->name }} </span>starts in 30 minutes! 

  Here are the details of your reservation:
  <ul>
    <li><span class="bold">Reservation number:</span> {{ $reservation->id }}</li>
    <li><span class="bold">Start date:</span> {{ $reservation->start_date }}</li>
    <li><span class="bold">End date:</span> {{ $reservation->end_date }}</li>
    <li><span class="bold">Start time:</span> {{ $reservation->start_hour }} (GMT+1)</li>
    <li><span class="bold">End time:</span> {{ $reservation->end_hour }} (GMT+1)</li>
    <li><span class="bold">Resource requested:</span> {{ $reservation->ressource->space->name }}</li>
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
    <li><span class="bold">Reservation made by:</span> {{ $reservation->createdBy->lastname }} {{ $reservation->createdBy->firstname }}</li>
  </ul>
  
  We remind you that if the payment is not effective or total, 
  the customer must pay before the start of the reservation time. 
  Additionally, if the <span class="bold">50%</span> 
  of the reservation have not been paid, there is no guarantee that 
  the resource is available in agency.

  To view the reservation, you can use the following link:
  <a href="{{ url($admin_url) }}" class="link">see reservation</a>

  We invite you to verify the payment and contact the customer if necessary.

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