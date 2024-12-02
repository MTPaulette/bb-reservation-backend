@php

$companyname = \Options::getValue('companyname');
$address = \Options::getValue('address');
$city = \Options::getValue('city');
$phonenumber = \Options::getValue('phonenumber');
$whatsapp = \Options::getValue('whatsapp');
$site_url = \Options::getValue('URL');
$facebook = \Options::getValue('facebook');
$instagram = \Options::getValue('instagram');
$linkedln = \Options::getValue('linkedln');
$twitter = \Options::getValue('twitter');

@endphp



<footer>
<!-- <a href="{{ url($whatsapp) }}"
    style="
        text-decoration: none !important;
        padding: 10px 30px; background: #0b9444; border-radius: 20px;
    ">
  <span style="color: white; font-weight: 700px; margin-left: 5px;">RESERVEZ MAINTENANT</span>
</a> -->
<hr size="2px" width="100%" color="black" style="margin-top: 40px; margin-bottom: 20px;" />
<p>
  <address>
    <b style="color:black; font-weight: 700px;">{{ $companyname }}</b>
    <br> {{ $address }}
    <br> {{ $city }}
    <br> Tel: {{ $phonenumber }}
    <br>
    <p style="display: flex; align-items: center;">
        <span>Follow us on social network:</span>
        <span>
            <a href="{{ url($site_url) }}" style="text-decoration: none; margin-left: 5px;">
                <img src="{{ asset('social/global.png') }}" width="17px" height="17px" alt="site-icon" />
            </a>
            <a href="{{ url($facebook) }}" style="text-decoration: none; margin-left: 5px;">
                <img src="{{ asset('social/facebook.png') }}" width="18px" height="18px" alt="site-icon" />
            </a>
            <a href="{{ url($instagram) }}" style="text-decoration: none; margin-left: 5px;">
                <img src="{{ asset('social/instagram.png') }}" width="17px" height="17px" alt="site-icon" />
            </a>
            <a href="{{ url($linkedln) }}" style="text-decoration: none; margin-left: 4px;">
                <img src="{{ asset('social/linkedln.png') }}" width="auto" height="19.5px" alt="site-icon" />
            </a>
            <a href="{{ url($twitter) }}" style="text-decoration: none; margin-left: 0px;">
                <img src="{{ asset('social/x.png') }}" width="auto" height="19px" alt="site-icon" />
            </a>
        </span>
    </p>
  </address>
</p>
</footer>