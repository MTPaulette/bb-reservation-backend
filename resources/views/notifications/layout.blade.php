
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
</head>
<body>
    <main id="main">
    @include('notifications.header')
        <!-- <h1>@yield('title')</h1> -->
      @yield('content')
    @include('notifications.footer')
    </main>
</body>
</html>


<style>
  body {
    background-color:rgb(159, 255, 199);
  }
  .name {
    color:black;
  }
  #main {
    position: relative;
    max-width: 100vw;
    padding: 32px;
    margin: 64px;
    box-shadow: 0 2px 0 rgba(0, 0, 150, 0.025), 2px 4px 0 rgba(0, 0, 150, 0.015);
    width: 570px;
    background-color: #fff;
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