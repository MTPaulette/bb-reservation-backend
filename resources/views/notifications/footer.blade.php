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
<!-- <a href="{{ asset($whatsapp) }}"
    style="
        text-decoration: none !important;
        padding: 10px 30px; background: #0b9444; border-radius: 20px;
    ">
  <span style="color: white; font-weight: 700px; margin-left: 5px;">RESERVEZ MAINTENANT</span>
</a> -->

<h2 style="color:black;">
  La Direction,
</h2>
<hr size="2px" width="100%" color="black" style="margin-top: 40px; margin-bottom: 10px; opacity:20%;" />
<p>
  <address style="color:#111; font-size:14px; opacity:90%;">
    <span style="font-weight: 700;">{{ $companyname }}</span>
    <br> {{ $address }}
    <br> {{ $city }}
    <br> Tel: {{ $phonenumber }}
    <br>
    <p style="display: flex; align-items: center;">
        <span style="color:#111; font-weight: 600; font-size:14px; opacity:90%;">Follow us on social network:</span>
        <span>
            <a href="{{ asset($site_url) }}" style="text-decoration: none; margin-left: 5px;">
                <img src="{{ asset('social/global.png') }}" width="17px" height="17px" alt="web-site-icon" />
            </a>
            <a href="{{ asset($facebook) }}" style="text-decoration: none; margin-left: 5px;">
                <img src="{{ asset('social/facebook.png') }}" width="18px" height="18px" alt="facebook-icon" />
            </a>
            <a href="{{ asset($instagram) }}" style="text-decoration: none; margin-left: 5px;">
                <img src="{{ asset('social/instagram.png') }}" width="17px" height="17px" alt="instagram-icon" />
            </a>
            <a href="{{ asset($linkedln) }}" style="text-decoration: none; margin-left: 4px;">
                <img src="{{ asset('social/linkedln.png') }}" width="auto" height="19.5px" alt="linkedin-icon" />
            </a>
            <a href="{{ asset($twitter) }}" style="text-decoration: none; margin-left: 0px;">
                <img src="{{ asset('social/x.png') }}" width="auto" height="19px" alt="x-icon" />
            </a>
        </span>
    </p>
  </address>
</p>
</footer>