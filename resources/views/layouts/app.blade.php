<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Cali</title>
  <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

  {{-- Styles --}}
  @stack('styles')
</head>
<body>

  {{-- Main Page Content --}}
  @yield('content')

  {{-- Scripts --}}
  @stack('scripts')

</body>
</html>
