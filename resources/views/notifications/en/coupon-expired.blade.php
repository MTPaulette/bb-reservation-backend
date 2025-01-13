@component('mail::message')
@include('notifications.header')

<h1 class="name">Hello {{ $notifiable->lastname }},</h1>
<div class="main-content">
  We remind you that the discount coupon {{ $coupon->name }} expired on {{ $coupon->expired_on }}.

  Here are the details of your coupon:
  <ul>
    <li><span class="bold">Coupon name:</span> {{ $coupon->name }}</li>
    <li><span class="bold">Coupon code:</span> {{ $coupon->code }}</li>
    <li><span class="bold">Discount value:</span> {{ $coupon->percent? $coupon->percent.' %': ''}} {{ $coupon->amount? $coupon->amount.' FCFA': '' }}</li>
    <li><span class="bold">Total usage:</span> {{ $coupon->total_usage }}</li>
    <li><span class="bold">Expiration date:</span> {{ $coupon->expired_on }}</li>
  </ul>

  We regret that you were not able to use this coupon before its 
  expiry. If you would like to continue to benefit from discounts and 
  special offers, we invite you to subscribe to our 
  newsletter or to follow our social networks.

  We thank you for your loyalty and we look forward to 
  welcome you to our establishment.

  Sincerely,
</div>
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
  .ps {
    font-weight: 600;
    font-size: 12px;
    color: red;
  }
</style>