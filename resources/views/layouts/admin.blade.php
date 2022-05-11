<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Página Inicial - Borderô</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="This is an example dashboard created using build-in elements and components.">
    <meta name="msapplication-tap-highlight" content="no">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/faviconglis.png') }}" />
    <!--
    =========================================================
    * ArchitectUI HTML Theme Dashboard - v1.0.0
    =========================================================
    * Product Page: https://dashboardpack.com
    * Copyright 2019 DashboardPack (https://dashboardpack.com)
    * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
    =========================================================
    * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    -->
    <link href="{{ asset('assets/css/general.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/system.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.inputmask.bundle.js') }}"></script>

    <link href="{{ asset('assets/css/bootstrap-combobox.css') }}" rel="stylesheet">
    <script src="{{ asset('assets/js/bootstrap-combobox.js') }}"></script>

</head>

<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <div class="app-header header-shadow">
            <div class="app-header__logo">
                <div> <img width="150" height="37" src="{{ asset('assets/images/logo_grlis.png') }}">
                </div>
                <div class="header__pane ml-auto">
                    <div>
                        <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                            data-class="closed-sidebar">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="app-header__mobile-menu">
                <div>
                    <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="app-header__menu">
                <span>
                    <button type="button"
                        class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                        <span class="btn-icon-wrapper">
                            <i class="fa fa-ellipsis-v fa-w-6"></i>
                        </span>
                    </button>
                </span>
            </div>
            <div class="app-header__content">
                <div class="app-header-right">
                    <div class="header-btn-lg pr-0">
                        <div class="widget-content p-0">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="btn-group">
                                        <div style="margin-top:8px;margin-right:14px;">
                                            Olá, {{ Auth::user()->name }}
                                        </div>
                                        <div>
                                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                               document.getElementById('logout-form').submit();">
                                                <i class="nav-link-icon fa fa-sign-out-alt"> </i> Sair
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-main">
            <div class="app-sidebar sidebar-shadow">
                <div class="app-header__logo">
                    <div class="logo-src"></div>
                    <div class="header__pane ml-auto">
                        <div>
                            <button type="button" class="hamburger close-sidebar-btn hamburger--elastic"
                                data-class="closed-sidebar">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="app-header__mobile-menu">
                    <div>
                        <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
                <div class="app-header__menu">
                    <span>
                        <button type="button"
                            class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                            <span class="btn-icon-wrapper">
                                <i class="fa fa-ellipsis-v fa-w-6"></i>
                            </span>
                        </button>
                    </span>
                </div>
                <div class="scrollbar-sidebar">
                    <div class="app-sidebar__inner">
                        <ul class="vertical-nav-menu">
                            <li class="app-sidebar__heading"></li>
                            <li>
                                <a href="{{route('admin')}}" class="{{request()->routeIs('admin')? 'mm-active' : ''}}">
                                    <i class="metismenu-icon pe-7s-home"></i>
                                    Página Inicial
                                </a>
                            </li>
                            <!--<li class="app-sidebar__heading">Borderô</li>
                            <li>
                                <a href="{{route('clientes.create')}}"
                                    class="{{request()->routeIs('clientes.create')? 'mm-active' : ''}}">
                                    <i class="metismenu-icon pe-7s-news-paper "></i>
                                    Inserir Borderô
                                    <i class="metismenu-state-icon"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('clientes.index')}}">
                                    <i class="metismenu-icon pe-7s-news-paper"></i>
                                    Editar Borderô
                                    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                                </a>
                                <ul class="{{request()->routeIs('clientes.index')? 'mm-show' : ''}}
                                    {{request()->routeIs('clientes/import')? 'mm-show' : ''}}">
                                    <li>
                                        <a href="{{route('clientes.index')}}"
                                            class="{{request()->routeIs('clientes.index')? 'mm-active' : ''}}">
                                            <i class="metismenu-icon pe-7s-user"></i>
                                            Pesquisar
                                            <i class="metismenu-state-icon pe-7s-search"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{route('clientes/import')}}"
                                            class="{{request()->routeIs('clientes/import')? 'mm-active' : ''}}">
                                            <i class="metismenu-icon fa fa-file-excel"></i>
                                            Novo
                                            <i class="metismenu-state-icon pe-7s-file"></i>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="app-sidebar__heading">Relatórios</li>
                            <li>
                                <a href="dashboard-boxes.html">
                                    <i class="metismenu-icon pe-7s-file"></i>
                                    Relatório
                                </a>
                            </li>-->
                        </ul>
                    </div>
                </div>
            </div>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    @yield('content')
                </div>
                <div class="app-wrapper-footer">
                    <div class="app-footer">
                        <div class="app-footer__inner">
                            <div class="app-footer-left">
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a href="javascript:void(0);" class="nav-link">
                                            Grupo Lis &copy; <?php echo date("Y"); ?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{ asset('assets/scripts/main.js') }}"></script>
</body>

</html>
