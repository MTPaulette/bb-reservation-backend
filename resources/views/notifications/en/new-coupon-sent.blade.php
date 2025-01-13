@component('mail::message')
@include('notifications.header')

<h1 class="name">Hello {{ $notifiable->lastname }},</h1>
<div class="main-content">
  We are delighted to announce that you have just received 
  a new discount coupon!

  Here are the details of your coupon:
  <ul>
    <li><span class="bold">Coupon name:</span> {{ $coupon->name }}</li>
    <li><span class="bold">Coupon code:</span> {{ $coupon->code }}</li>
    <li><span class="bold">Discount value:</span> {{ $coupon->percent? $coupon->percent.' %': ''}} {{ $coupon->amount? $coupon->amount.' FCFA': '' }}</li>
    <li><span class="bold">Total usage:</span> {{ $coupon->total_usage }}</li>
    <li><span class="bold">Expiration date:</span> {{ $coupon->expired_on }}</li>
  </ul>

  You can use this coupon to get a discount on your next 
  reservation. To use the coupon, simply enter 
  the coupon code when booking.

  We thank you for your loyalty and we look forward to seeing you 
  welcome to our establishment.

  Sincerely,
</div>
<h2 style="color:black;">
  The Management.
</h2>
<p class="ps">
  P.S. Don't forget to check the terms of use of the coupon before using it.
</p>
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