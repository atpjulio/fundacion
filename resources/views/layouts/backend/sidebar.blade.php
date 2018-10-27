<aside class="sidebar">
    <div class="sidebar-container">

        <div class="sidebar-header">
            <div class="brand">
                <div class="logo">
                    <img src="{{ asset('img/logo.png') }}" height="60" style="margin-top: -36px;">
                    <!--
                    <span class="l l1"></span>
                    <span class="l l2"></span>
                    <span class="l l3"></span>
                    <span class="l l4"></span>
                    <span class="l l5"></span>
                    -->
                </div>
                {!! config('constants.companyInfo.name') !!}
            </div>
        </div>

        <nav class="menu">
            <ul class="sidebar-menu metismenu" id="sidebar-menu">

                <li class="@if(Request::is('home*')) active @endif">
                    <a href="{{ route('home') }}">
                        <i class="fa fa-home"></i> Inicio
                    </a>
                </li>
                @role('admin|user')
                <li class="@if(Request::is('authorization*')) open active @endif">
                    <a href="">
                        <i class="far fa-clipboard"></i> Autorizaciones
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="@if(Request::is('authorization/create')) active @endif" >
                            <a href="{{ route('authorization.create') }}">
                                <i class="fa fa-plus"></i>&nbsp;
                                Nueva Autorización
                            </a>
                        </li>
                        <li class="@if(Request::is('authorization')) active @endif" >
                            <a href="{{ route('authorization.index') }}">
                                <i class="fas fa-list"></i>&nbsp;
                                Listado
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                @role('admin|user')
                <li class="@if(Request::is('patient*')) open active @endif">
                    <a href="">
                        <i class="fa fa-wheelchair"></i> Usuarios
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="@if(Request::is('patient/create')) active @endif" >
                            <a href="{{ route('patient.create') }}">
                                <i class="fa fa-plus"></i>&nbsp;
                                Nuevo Usuario
                            </a>
                        </li>
                        <li class="@if(Request::is('patient')) active @endif" >
                            <a href="{{ route('patient.index') }}">
                                <i class="fas fa-list"></i>&nbsp;
                                Listado de Usuarios
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                @role('admin')
                <li class="@if(Request::is('invoice*') or Request::is('rip*')) open active @endif">
                    <a href="">
                        <i class="fas fa-file-invoice-dollar"></i> Facturas
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="@if(Request::is('invoice/create')) active @endif" >
                            <a href="{{ route('invoice.create') }}">
                                <i class="fa fa-plus"></i>&nbsp;
                                Nueva Factura
                            </a>
                        </li>
                        <li class="@if(Request::is('invoice')) active @endif" >
                            <a href="{{ route('invoice.index') }}">
                                <i class="fas fa-list"></i>&nbsp;
                                Listado de Facturas
                            </a>
                        </li>
                        <li class="@if(Request::is('invoice-relation')) active @endif" >
                            <a href="{{ route('invoice.relation') }}">
                                <i class="fas fa-crop-alt"></i>&nbsp;
                                Relación de Facturas
                            </a>
                        </li>
                        <li class="@if(Request::is('rip*')) active @endif" >
                            <a href="{{ route('rip.index') }}">
                                <i class="far fa-file-archive"></i>&nbsp;
                                RIPS
                            </a>
                        </li>                        
                    </ul>
                </li>
                @endrole
                @role('admin')
                <li class="@if(Request::is('eps*')) open active @endif">
                    <a href="">
                        <i class="fa fa-hospital-o"></i> EPS
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="@if(Request::is('eps/create')) active @endif" >
                            <a href="{{ route('eps.create') }}">
                                <i class="fa fa-plus"></i>&nbsp;
                                Añadir EPS
                            </a>
                        </li>
                        <li class="@if(Request::is('eps')) active @endif" >
                            <a href="{{ route('eps.index') }}">
                                <i class="fas fa-list"></i>&nbsp;
                                Listado de EPS
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                @role('admin')
                <li class="@if(Request::is('accounting*') or Request::is('egress*') or Request::is('receipt*')) open active @endif">
                    <a href="">
                        <i class="fas fa-calculator"></i> Contabilidad
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="@if(Request::is('egress*')) active @endif" >
                            <a href="{{ route('egress.index') }}">
                                <i class="fas fa-file-export"></i>&nbsp;
                                Comprobante de Egreso
                            </a>
                        </li>
                        <li class="@if(Request::is('accounting/eps*')) active @endif" >
                            <a href="{{ route('accounting.eps') }}">
                                <i class="fa fa-hospital-o"></i>&nbsp;
                                Contabilidad EPS
                            </a>
                        </li>
                        <li class="@if(Request::is('accounting-note*')) active @endif" >
                            <a href="{{ route('accounting-note.index') }}">
                                <i class="far fa-sticky-note"></i>&nbsp;
                                Nota Interna
                            </a>
                        </li>
                        <li class="@if(Request::is('receipt*')) active @endif" >
                            <a href="{{ route('receipt.index') }}">
                                <i class="fas fa-file-invoice"></i>&nbsp;
                                Recibos
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                @role('admin')
                <li class="@if(Request::is('company*')) open active @endif">
                    <a href="">
                        <i class="far fa-building"></i> Compañías
                        <i class="fa arrow"></i>
                    </a>
                    <ul class="sidebar-nav">
                        <li class="@if(Request::is('company/create')) active @endif" >
                            <a href="{{ route('company.create') }}">
                                <i class="fa fa-plus"></i>&nbsp;
                                Nueva Compañía
                            </a>
                        </li>                        
                        <li class="@if(Request::is('company')) active @endif" >
                            <a href="{{ route('company.index') }}">
                                <i class="fas fa-list"></i>&nbsp;
                                Listado de Compañías
                            </a>
                        </li>
                    </ul>
                </li>
                @endrole
                {{--
                <li class="active open" >
                    <a href="">
                        <i class="fa fa-area-chart"></i> Charts
                        <i class="fa arrow"></i>
                    </a>

                    <ul class="sidebar-nav">
                        <li class="active" >
                            <a href="charts-flot.html">
                                Flot Charts
                            </a>
                        </li>

                        <li class="active" >
                            <a href="charts-morris.html">
                                Morris Charts
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="active open" >
                    <a href="">
                        <i class="fa fa-table"></i> Tables
                        <i class="fa arrow"></i>
                    </a>

                    <ul class="sidebar-nav">
                        <li class="active" >
                            <a href="static-tables.html">
                                Static Tables
                            </a>
                        </li>

                        <li class="active" >
                            <a href="responsive-tables.html">
                                Responsive Tables
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="active" >
                    <a href="forms.html">
                        <i class="fa fa-pencil-square-o"></i> Forms
                    </a>
                </li>

                <li class="active open">
                    <a href="">
                        <i class="fa fa-desktop"></i> UI Elements
                        <i class="fa arrow"></i>
                    </a>

                    <ul class="sidebar-nav">
                        <li class="active">
                            <a href="buttons.html">
                                Buttons
                            </a>
                        </li>

                        <li class="active">
                            <a href="cards.html">
                                Cards
                            </a>
                        </li>

                        <li class="active">
                            <a href="typography.html">
                                Typography
                            </a>
                        </li>

                        <li class="active">
                            <a href="icons.html">
                                Icons
                            </a>
                        </li>

                        <li class="active">
                            <a href="grid.html">
                                Grid
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="active open">

                    <a href="">
                        <i class="fa fa-file-text-o"></i> Pages
                        <i class="fa arrow"></i>
                    </a>

                    <ul class="sidebar-nav">
                        <li class="active">
                            <a href="login.html">
                                Login
                            </a>
                        </li>

                        <li class="active">
                            <a href="signup.html">
                                Sign Up
                            </a>
                        </li>

                        <li class="active">
                            <a href="reset.html">
                                Reset
                            </a>
                        </li>

                        <li class="active">
                            <a href="error-404.html">
                                Error 404 App
                            </a>
                        </li>

                        <li class="active">
                            <a href="error-404-alt.html">
                                Error 404 Global
                            </a>
                        </li>

                        <li class="active">
                            <a href="error-500.html">
                                Error 500 App
                            </a>
                        </li>

                        <li class="active">
                            <a href="error-500-alt.html">
                                Error 500 Global
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="">
                        <i class="fa fa-sitemap"></i> Menu Levels
                        <i class="fa arrow"></i>
                    </a>

                    <ul class="sidebar-nav">

                        <li>
                            <a href="#">
                                Second Level Item
                                <i class="fa arrow"></i>
                            </a>

                            <ul class="sidebar-nav">

                                <li>
                                    <a href="#">
                                        Third Level Item
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        Third Level Item
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li>
                            <a href="#">
                                Second Level Item
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Second Level Item
                                <i class="fa arrow"></i>
                            </a>

                            <ul class="sidebar-nav">

                                <li>
                                    <a href="#">
                                        Third Level Item
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        Third Level Item
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        Third Level Item
                                        <i class="fa arrow"></i>
                                    </a>

                                    <ul class="sidebar-nav">

                                        <li>
                                            <a href="#">
                                                Fourth Level Item
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#">
                                                Fourth Level Item
                                            </a>
                                        </li>
                                    </ul>

                                </li>
                            </ul>
                        </li>

                    </ul>
                </li>

                <li class="active" >
                    <a href="screenful.html">
                        <i class="fa fa-bar-chart"></i> Agile Metrics <span class="label label-screenful">by Screenful</span>
                    </a>
                </li>

                <li class="active" >
                    <a href="https://github.com/modularcode/modular-admin-html">
                        <i class="fa fa-github-alt"></i> Theme Docs
                    </a>
                </li>
                --}}

            </ul>
        </nav>

    </div>

    {{--> app/_common/sidebar/customize/customize--}}

</aside>
<div class="sidebar-overlay" id="sidebar-overlay"></div>
<div class="sidebar-mobile-menu-handle" id="sidebar-mobile-menu-handle"></div>
