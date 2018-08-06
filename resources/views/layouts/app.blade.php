<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Projeto SARA</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <!-- Styles -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
   <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" style="visibility:hidden">
        @auth
            <s-app login=true logout="{{ route('logout') }}"  token= "{{ csrf_token() }}" user="{{ Auth::user()->nome }}">
                <div class="ml-3 mr-3">
                    @yield('content')
                </div> 
            </s-app>      
        @endauth
        @guest
            <s-app login=false logout="{{ route('logout') }}"  token= "{{ csrf_token() }}">
                <div class="ml-3 mr-3">
                    @yield('content')
                </div>
            </s-app>   
        @endguest
    </div>
</body>
</html>
