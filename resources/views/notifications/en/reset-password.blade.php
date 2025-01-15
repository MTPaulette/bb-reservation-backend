@component('mail::message')
@include('notifications.header')

<h1 class="name">Password Reset</h1>
<div class="main-content">
  <p>Hello {{ $notifiable->lastname }},</p>
  You have requested a password reset. To reset your password, click on the 
  following link: <a href="{{ url($link) }}" class="link">Reset my password.</a>


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