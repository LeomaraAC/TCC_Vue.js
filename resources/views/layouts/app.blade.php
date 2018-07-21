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

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">
    <!-- Styles -->
    <!-- <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet"> -->
     <!-- <link href='https://cdn.jsdelivr.net/npm/vuetify@1.1.6/dist/vuetify.min.css'  rel="stylesheet"> -->
     <link href="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css" rel="stylesheet">
     <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" style="visibility:hidden">
        @auth
            <sara-app login=true logout="{{ route('logout') }}"  token= "{{ csrf_token() }}" user="{{ Auth::user()->nome }}">
                <div class="ml-3 mr-3">
                    @yield('content')
                </div> 
            </sara-app>      
        @endauth
        @guest
            <sara-app login=false logout="{{ route('logout') }}"  token= "{{ csrf_token() }}">
                <div class="ml-3 mr-3">
                    @yield('content')
                </div>
            </sara-app>   
        @endguest
    </div>
</body>
</html>
