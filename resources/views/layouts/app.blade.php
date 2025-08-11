<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tanjung Lesung Beach Resort</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Custom CSS -->
    <link href="{{ asset('frontend/assets/css/style.css') }}" rel="stylesheet">
    
    <!-- Additional CSS from the page -->

        @yield('style.css')

</head>
<body>
    <!-- Include Navbar -->
    @include('frontend.body.navbar')
    
    <!-- Main Content -->
    @yield('content')
    
    <!-- Include Footer -->
    @include('frontend.body.footer')
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Additional Scripts -->
    @yield('scripts')
</body>
</html>