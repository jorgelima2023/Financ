<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Financeiro</title>
</head>

<body class="sb-nav-fixed">

    <!-- <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark"> -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-nav">

        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="#">Financeiro</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
           
        </form>
        <!--
        ------------------------------- Navbar ---------------------------------------------------
        -->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.show') }}">Perfil</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="{{ route('login.destroy') }}">Sair</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!--
    ------------------------------- Navbar - menus ---------------------------------------------------
    -->
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <!-- <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion"> -->
            <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">

                <div class="sb-sidenav-menu">
                    <div class="nav fs-7">
                        <!-- ------------------------ Dashboard -------------------------------   -->
                        <a @class(['nav-link', 'active' => isset($menu) && $menu == 'dashboard']) href="{{ route('dashboard.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>

                        <!-- ------------------------ usuarios -------------------------------   -->
                        @can('index-user')
                            <a @class(['nav-link', 'active' => isset($menu) && $menu == 'users']) href="{{ route('user.index') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                                Usuários
                            </a>
                        @endcan

                        <!-- ------------------------ Papéis -------------------------------   -->
                        @can('index-role')
                            <a @class(['nav-link', 'active' => isset($menu) && $menu == 'roles']) href="{{ route('role.index') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-network-wired"></i></div>
                                Papéis
                            </a>
                        @endcan

                        {{-- ============== categorias desativado item de menu =========================== --}}
                        {{-- @can('index-categoria') --}}
                            <!-- <a class="nav-link" href="{{ route('categorias.index') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                                Categorias
                            </a> -->
                        {{-- @endcan --}}

                        {{-- ============== categorias formulario =========================== --}}
                        <!-- ------------------------ Categorias-formulario -------------------------------   -->
                        @can('form-categoria')
                            <a class="nav-link" href="{{ route('categorias.form') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                                Categorias
                            </a>
                        @endcan

                        {{-- ============== Formas Pagamento formulario =========================== --}}
                        <!-- ------------------------ Formas Pagamento-formulario -------------------------------   -->
                        @can('form-forma_pgto')
                            <a class="nav-link" href="{{ route('formaPagamentos.form') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                                Formas Pagamento
                            </a>
                        @endcan

                        <a class="nav-link" href="{{ route('contaCorrente.form') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                                Conta Corrente
                            </a>

                        <!-- ------------------------ Movimentos -------------------------------   -->
                        @can('index-movimento')
                            <a @class(['nav-link', 'active' => isset($menu) && $menu == 'movimentos']) href="{{ route('movimentos.index') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                                Movimentos
                            </a>
                        @endcan

                        <!-- ------------------------ Movimentos - relatório -------------------------------   -->
                         @can('index-movimento')
                            <a @class(['nav-link', 'active' => isset($menu) && $menu == 'movimentos-relatorio']) href="{{ route('movimentos.relatorioselecao') }}">
                                <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                                Movimentos - relatório - Obrigação/Provisão
                            </a>
                        @endcan

                        <a class="nav-link" href="{{ route('login.destroy') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-arrow-right-from-bracket"></i></div>
                            Sair
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logado:
                        @if ( auth()->check() )
                            {{ auth()->user()->name }} 
                            <br />{{ auth()->user()->roles[0]->name }}        
                        @endif
                    </div>
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">

            <main>
                @yield('content')
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Financeiro {{ date('Y') }}</div>
                        <div>
                            <a href="#" class="text-decoration-none">Política de Privacidade</a>
                            &middot;
                            <a href="#" class="text-decoration-none">Termos de Uso</a>
                        </div>
                    </div>
                </div>
            </footer>

        </div>

    </div>
   
</body>

</html>