<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Portal Borderô</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.rawgit.com/mfd/09b70eb47474836f25a21660282ce0fd/raw/e06a670afcb2b861ed2ac4a1ef752d062ef6b46b/Gilroy.css">
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">
    <style>
        p {
            font: 35px 'Gilroy', sans-serif;
            text-align: center;
            margin-top: 10%;
            margin-left: 10%;
        }

        h1 {
            font: 35px 'Gilroy', sans-serif;
            text-align: center;
            color: #ac9000;
            margin-bottom: 50px;
            margin-left: 10%;
        }

        .embaixo {
            margin-bottom: 20px;
            width: 50%;
            margin-left:16%;
        }
    </style>
    <style>
        /*  html, body {
                background-image:url('assets/images/login.png');
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }*/
    </style>
</head>

<body style="background-image: url('{{ asset('assets/images/fregistra.png') }}');  background-size: cover; width:90%;">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <p>Seja bem vindo! </p>
            <h1><strong>Portal Borderô</strong></h1>
        </div>
        <div class="embaixo">
            <!--<div class="row embaixo">
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg btn-block"
                style="font-size : 20px; width: 100%;" >Cadastro</a>
            </div>-->
            <div class="row embaixo">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-block"
                style="font-size : 20px; width: 100%;">Cedente</a>
            </div>
            <div class="row embaixo">
                <a href="{{ route('admin.login') }}" class="btn btn-primary btn-lg btn-block"
                style="font-size : 20px; width: 100%;">Operador</a>
            </div>
        </div>
    </div>
    <!--<div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Cedentes</a>
                        <a href="{{ route('admin.login') }}">Operadores</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Registrar</a>
                        @endif
                    @endauth
                </div>


            @endif


            <div class="content">
            <!--<img class="center" src="{{ asset('assets/images/logo_grlis.png') }}">
                <div class="title m-b-md">

                    Portal Borderô
                </div>

                <div class="links">
                    <a href="https://laravel.com/docs"></a>
                   <!--<a href="https://grlis.com.br/">Grupo Lis</a>
                    <a href="https://laravel-news.com">News</a>
                    <a href="https://blog.laravel.com">Blog</a>
                    <a href="https://nova.laravel.com">Nova</a>
                    <a href="https://grlis.com.br/about/">Sobre</a>
                    <a href="https://grlis.com.br/contact/">Contato</a>
                    <a href="https://github.com/laravel/laravel">Suporte</a>-->
    <!--</div>-->
    <!--</div>-->
    </div>

</body>

</html>
