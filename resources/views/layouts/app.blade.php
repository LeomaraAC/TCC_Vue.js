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
            <div class="wrapper application ">
                <s-sidebar>
                    @include('layouts.menuSidebar')
                </s-sidebar>
                <div id="content">
                    <s-navbar logout="{{ route('logout') }}" 
                        token="{{ csrf_token() }}" user="{{ Auth::user()->nome }}"></s-navbar>
                    <main class="py-4">
                        <div class="ml-3 mr-3">
                            @yield('content')
                        </div> 
                    </main>
                </div>
            </div> 
        @endauth
        @guest
        <div class="wrapper application ">
            <div id="content">
                <main class="py-4">
                    <div class="ml-3 mr-3">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
        @endguest
    </div>
</body>
</html>
