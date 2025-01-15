@component('mail::message')
@include('notifications.header')

<h1 class="name">Réinitialisation de mot de passe</h1>
<div class="main-content">
  <p>Hello {{ $notifiable->lastname }},</p>
  Vous avez demandé la réinitialisation de votre mot de passe. Pour réinitialiser votre mot de passe, 
  cliquez sur le lien suivant : 
  <a href="{{ url($link) }}" class="link">Réinitialiser mon mot de passe.</a><br /><br />

  Cordialement,
</div>
<h2 style="color:black;">
  La direction.
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