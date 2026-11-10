import 'bootstrap';
import './style.css';
import {
    renderMovimentos,
    initMovimentos
} from './pages/movimentos.js';

const app = document.querySelector('#app');

function login() {
    app.innerHTML = `
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header">
                                        <h3 class="text-center login-title my-4">Financ</h3>
                                    </div>

                                    <div class="card-body">
                                        <form id="loginForm">
                                            <div class="form-floating mb-3">
                                                <input type="email"
                                                    class="form-control"
                                                    id="email"
                                                    placeholder="Digite o e-mail">
                                                <label for="email">E-mail</label>
                                            </div>

                                            <div class="form-floating mb-3">
                                                <input type="password"
                                                    class="form-control"
                                                    id="password"
                                                    placeholder="Digite a senha">
                                                <label for="password">Senha</label>
                                            </div>

                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a href="#/login" class="small text-decoration-none">
                                                    Esqueceu a senha?
                                                </a>

                                                <button type="submit" class="btn btn-primary">
                                                    Acessar
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="card-footer text-center py-3">
                                        <div class="small">
                                            Precisa de uma conta?
                                            <a href="#/login" class="text-decoration-none">
                                                Inscrever-se!
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    `;

    document.body.className = 'bg-primary';

    document.querySelector('#loginForm').addEventListener('submit', event => {
        event.preventDefault();
        location.hash = '/dashboard';
    });
}

function dashboard() {
    document.body.className = '';

    app.innerHTML = `
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-nav">

            <a class="navbar-brand ps-3" href="#/dashboard">Financ</a>

            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4"
                id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="navbar-nav ms-auto me-3">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle"
                        id="navbarDropdown"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown">
                        <i class="fas fa-user fa-fw"></i>
                        Usuário
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#/login">Sair</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div id="layoutSidenav">

            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-five"
                    id="sidenavAccordion">

                    <div class="sb-sidenav-menu">
                        <div class="nav">

                            <div class="sb-sidenav-menu-heading">Principal</div>

                            <a class="nav-link active" href="#/dashboard">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-tachometer-alt"></i>
                                </div>
                                Dashboard
                            </a>

                            <div class="sb-sidenav-menu-heading">Cadastros</div>

                            <a class="nav-link collapsed"
                                href="#"
                                data-bs-toggle="collapse"
                                data-bs-target="#cadastrosMenu">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-columns"></i>
                                </div>
                                Cadastros
                                <div class="sb-sidenav-collapse-arrow">
                                    <i class="fas fa-angle-down"></i>
                                </div>
                            </a>

                            <div class="collapse" id="cadastrosMenu">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="#/dashboard">Usuários</a>
                                    <a class="nav-link" href="#/dashboard">Papéis</a>
                                    <a class="nav-link" href="#/dashboard">Categorias</a>
                                    <a class="nav-link" href="#/dashboard">Formas Pagamento</a>
                                    <a class="nav-link" href="#/dashboard">Conta Corrente</a>
                                </nav>
                            </div>

                            <div class="sb-sidenav-menu-heading">Financeiro</div>

                            <a class="nav-link" href="#/movimentos">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-money-bill"></i>
                                </div>
                                Movimentos
                            </a>

                            <a class="nav-link" href="#/dashboard">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                Movimentos em lote
                            </a>

                            <a class="nav-link" href="#/dashboard">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-file-import"></i>
                                </div>
                                Movimentos importar
                            </a>

                            <a class="nav-link" href="#/dashboard">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                Relatórios
                            </a>

                        </div>
                    </div>

                    <div class="sb-sidenav-footer">
                        <div class="small">Logado:</div>
                        Usuário demonstração
                    </div>

                </nav>
            </div>

            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">

                        <h1 class="mt-4">Dashboard</h1>

                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>

                        <div class="row">

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card bg-primary text-white card-dashboard">
                                    <div class="card-body">Receitas</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span>Visualizar detalhes</span>
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card bg-warning text-white card-dashboard">
                                    <div class="card-body">Despesas</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span>Visualizar detalhes</span>
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card bg-success text-white card-dashboard">
                                    <div class="card-body">Saldo</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span>Visualizar detalhes</span>
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card bg-danger text-white card-dashboard">
                                    <div class="card-body">Contas</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span>Visualizar detalhes</span>
                                        <i class="fas fa-angle-right"></i>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </main>

                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="text-muted small">
                            Copyright &copy; Financ 2026
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    `;

    const sidebarToggle = document.querySelector('#sidebarToggle');

    sidebarToggle.addEventListener('click', event => {
        event.preventDefault();
        document.body.classList.toggle('sb-sidenav-toggled');
    });
}

function render() {

    const rota = location.hash;

    if (rota === '#/dashboard') {

        dashboard();

    } else if (rota === '#/movimentos') {

        // Primeiro monta o layout SB Admin
        dashboard();

        // Localiza o conteúdo principal
        const main = document.querySelector(
            '#layoutSidenav_content main'
        );

        // Renderiza a página de movimentos
        main.innerHTML = renderMovimentos();

        // Inicializa filtros, tabela, cadastro e gráficos
        initMovimentos();

    } else {

        login();

    }
}

window.addEventListener('hashchange', render);

render();