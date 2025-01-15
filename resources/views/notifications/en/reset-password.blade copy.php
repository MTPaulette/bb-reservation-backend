
@extends('notifications.layout')

@section('title', 'Réinitialisation de mot de passe')

@section('content')
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
@endsection
