<!DOCTYPE html>
<html lang="en">

<head>
    <title>Portal Borderô | Operadores </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}" />
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset ('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/index_main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="https://cdn.rawgit.com/mfd/09b70eb47474836f25a21660282ce0fd/raw/e06a670afcb2b861ed2ac4a1ef752d062ef6b46b/Gilroy.css">
    <!--===============================================================================================-->
    <style>
        h1 {
        font: 'Gilroy', sans-serif;
        }
        </style>
</head>

<body>
    <div class="limiter">
        <div class="container-login100" style="background-image: url('{{ asset('assets/images/login.jpeg') }}');">
            <div class="wrap-login100 p-t-30 p-b-50">
                <form class="login100-form validate-form p-b-33 p-t-5" method="POST"
                    action="{{ route('admin.login.submit') }}">
                    @csrf
                    <h1 style="text-align:center">Operadores</h1>
                    <img class="center" src="{{ asset('assets/images/logo_grlis.png') }}">
                    <div class="text-center">
                        
                    </div>
                    <div style="margin-top:20px;">
                        @if ($errors->has('g-recaptcha-response'))
                        <div class="card-footer">
                            <div class="alert alert-danger" role="alert">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-right cor_preta">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-right cor_preta">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="center">
                            {!! NoCaptcha::renderJs() !!}
                            {!! NoCaptcha::display() !!}
                        </div>
                    </div>
                    <div class="container-login100-form-btn m-t-32">
                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>
                    </div>
                    <div class="div_esqueceu">
                        @if (Route::has('password.request'))
                        <a class="btn btn-link" style="color: black;" href="{{ route('password.request') }}">
                            {{ __('Esqueceu a senha?') }}
                        </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>