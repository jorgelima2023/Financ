
const usuarios = [
    {
        id: 1,
        nome: 'Administrador',
        email: 'admin@financ.com.br',
        papel: 'Administrador',
        cadastro: '2026-10-15T09:30'
    },
    {
        id: 2,
        nome: 'João Silva',
        email: 'joao@email.com',
        papel: 'Usuário',
        cadastro: '2026-10-20T14:15'
    },
    {
        id: 3,
        nome: 'Maria Oliveira',
        email: 'maria@email.com',
        papel: 'Financeiro',
        cadastro: '2026-11-02T10:00'
    },
    {
        id: 4,
        nome: 'Carlos Souza',
        email: 'carlos@email.com',
        papel: 'Usuário',
        cadastro: '2026-11-08T16:45'
    }
];

let usuariosFiltrados = [...usuarios];

export function renderUsuarios() {
    return `
        <div class="container-fluid px-4">

            <div class="mb-1 hstack gap-2">
                <h2 class="mt-3">Usuário</h2>

                <ol class="breadcrumb mb-3 mt-3 ms-auto">
                    <li class="breadcrumb-item">
                        <a href="#/dashboard" class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Usuários</li>
                </ol>
            </div>

            <!-- Pesquisa -->
            <div class="card mb-4 border-light shadow">
                <div class="card-header">
                    <span>Pesquisar</span>
                </div>

                <div class="card-body">
                    <form id="formPesquisaUsuarios">

                        <div class="row mb-3">
                            <div class="col-md-4 col-sm-12">
                                <label for="filtroNome" class="form-label">Nome</label>
                                <input
                                    type="text"
                                    name="name"
                                    id="filtroNome"
                                    class="form-control"
                                    placeholder="Nome do usuário"
                                >
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <label for="filtroEmail" class="form-label">E-mail</label>
                                <input
                                    type="text"
                                    name="email"
                                    id="filtroEmail"
                                    class="form-control"
                                    placeholder="E-mail do usuário"
                                >
                            </div>

                            <div class="col-md-4 col-sm-12 mt-4 pt-3">
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    Pesquisar
                                </button>

                                <button type="reset" id="btnLimparUsuarios"
                                    class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-trash"></i>
                                    Limpar
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-sm-12">
                                <label for="filtroDataInicio" class="form-label">
                                    Cadastro de
                                </label>
                                <input
                                    type="datetime-local"
                                    name="start_date_registration"
                                    id="filtroDataInicio"
                                    class="form-control"
                                >
                            </div>

                            <div class="col-md-4 col-sm-12">
                                <label for="filtroDataFim" class="form-label">
                                    Cadastro até
                                </label>
                                <input
                                    type="datetime-local"
                                    name="end_date_registration"
                                    id="filtroDataFim"
                                    class="form-control"
                                >
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <!-- Listagem -->
            <div class="card mb-4 border-light shadow">

                <div class="card-header hstack gap-2">
                    <span>Listar</span>

                    <div class="ms-auto d-flex flex-wrap gap-1">
                        <button type="button" id="btnCadastrarUsuario"
                            class="btn btn-success btn-sm">
                            <i class="fa-regular fa-square-plus"></i>
                            Cadastrar
                        </button>

                        <button type="button" id="btnPdfUsuarios"
                            class="btn btn-warning btn-sm">
                            <i class="fa-regular fa-file-pdf"></i>
                            Gerar PDF
                        </button>

                        <button type="button" id="btnCsvUsuarios"
                            class="btn btn-success btn-sm">
                            <i class="fa-regular fa-file-excel"></i>
                            Gerar Planilha
                        </button>
                    </div>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 70px;">ID</th>
                                    <th>Nome</th>
                                    <th class="d-none d-md-table-cell">E-mail</th>
                                    <th class="d-none d-md-table-cell">Papel</th>
                                    <th class="text-center" style="min-width: 260px;">Ações</th>
                                </tr>
                            </thead>

                            <tbody id="tbodyUsuarios">
                                ${renderLinhasUsuarios()}
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted" id="totalUsuarios">
                            ${usuariosFiltrados.length} usuários
                        </small>

                        <nav aria-label="Paginação de usuários">
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item disabled">
                                    <span class="page-link">Anterior</span>
                                </li>
                                <li class="page-item active">
                                    <span class="page-link">1</span>
                                </li>
                                <li class="page-item disabled">
                                    <span class="page-link">Próxima</span>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <div id="mensagemUsuarios" class="mt-3" aria-live="polite"></div>

                </div>
            </div>
        </div>
    `;
}

function renderLinhasUsuarios() {
    if (usuariosFiltrados.length === 0) {
        return `
            <tr>
                <td colspan="5" class="text-center text-muted py-4">
                    Nenhum usuário encontrado!
                </td>
            </tr>
        `;
    }

    return usuariosFiltrados.map(usuario => `
        <tr data-id="${usuario.id}">
            <th>${usuario.id}</th>
            <td>${escapeHtml(usuario.nome)}</td>
            <td class="d-none d-md-table-cell">
                ${escapeHtml(usuario.email)}
            </td>
            <td class="d-none d-md-table-cell">
                ${escapeHtml(usuario.papel || '-')}
            </td>
            <td>
                <div class="d-flex flex-row flex-wrap justify-content-center gap-1">

                    <button type="button"
                        class="btn btn-primary btn-sm btn-visualizar-usuario"
                        data-id="${usuario.id}">
                        <i class="fa-regular fa-eye"></i>
                        Visualizar
                    </button>

                    <button type="button"
                        class="btn btn-warning btn-sm btn-editar-usuario"
                        data-id="${usuario.id}">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Editar
                    </button>

                    <button type="button"
                        class="btn btn-danger btn-sm btn-apagar-usuario"
                        data-id="${usuario.id}">
                        <i class="fa-regular fa-trash-can"></i>
                        Apagar
                    </button>

                </div>
            </td>
        </tr>
    `).join('');
}

function escapeHtml(valor) {
    return String(valor ?? '').replace(/[&<>"']/g, caractere => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    })[caractere]);
}

function atualizarTabelaUsuarios() {
    const tabela = document.querySelector('#tbodyUsuarios');
    const total = document.querySelector('#totalUsuarios');

    if (tabela) tabela.innerHTML = renderLinhasUsuarios();
    if (total) total.textContent = `${usuariosFiltrados.length} usuários`;
}

function exibirMensagemUsuario(mensagem, tipo = 'info') {
    const elemento = document.querySelector('#mensagemUsuarios');

    if (elemento) {
        elemento.innerHTML = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${mensagem}
                <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        `;
    }
}

export function initUsuarios() {
    const formulario = document.querySelector('#formPesquisaUsuarios');
    const tabela = document.querySelector('#tbodyUsuarios');

    if (!formulario || !tabela) return;

    // Pesquisa
    formulario.addEventListener('submit', event => {
        event.preventDefault();

        const nome = document.querySelector('#filtroNome').value
            .trim().toLowerCase();

        const email = document.querySelector('#filtroEmail').value
            .trim().toLowerCase();

        const dataInicio = document.querySelector('#filtroDataInicio').value;
        const dataFim = document.querySelector('#filtroDataFim').value;

        usuariosFiltrados = usuarios.filter(usuario => {
            const correspondeNome = usuario.nome.toLowerCase().includes(nome);
            const correspondeEmail = usuario.email.toLowerCase().includes(email);
            const correspondeInicio = !dataInicio || usuario.cadastro >= dataInicio;
            const correspondeFim = !dataFim || usuario.cadastro <= dataFim;

            return correspondeNome &&
                correspondeEmail &&
                correspondeInicio &&
                correspondeFim;
        });

        atualizarTabelaUsuarios();
    });

    // Limpar filtros
    document.querySelector('#btnLimparUsuarios')
        .addEventListener('click', () => {
            usuariosFiltrados = [...usuarios];
            atualizarTabelaUsuarios();
            document.querySelector('#mensagemUsuarios').innerHTML = '';
        });

    // Botões do cabeçalho
    document.querySelector('#btnCadastrarUsuario')
        .addEventListener('click', () => {
            exibirMensagemUsuario(
                'Demonstrativo, a tela de cadastro de usuários não será apresentada.'
            );
        });

    document.querySelector('#btnPdfUsuarios')
        .addEventListener('click', () => {
            exibirMensagemUsuario(
                'Demonstrativo, a geração de PDF não será apresentada.'
            );
        });

    document.querySelector('#btnCsvUsuarios')
        .addEventListener('click', () => {
            exibirMensagemUsuario(
                'Demonstrativo, a geração de planilha não será apresentada.'
            );
        });

    // Ações da tabela
    tabela.addEventListener('click', event => {
        const botao = event.target.closest('button');
        if (!botao) return;

        const id = Number(botao.dataset.id);
        const usuario = usuarios.find(item => item.id === id);

        if (!usuario) return;

        if (botao.classList.contains('btn-visualizar-usuario')) {
            exibirMensagemUsuario(
                `Demonstrativo, a visualização do usuário <strong>${escapeHtml(usuario.nome)}</strong> .`
            );
        }

        if (botao.classList.contains('btn-editar-usuario')) {
            exibirMensagemUsuario(
                `Demonstrativo, a Edição do usuário <strong>${escapeHtml(usuario.nome)}</strong> não será apresentada.`
            );
        }

        if (botao.classList.contains('btn-apagar-usuario')) {
            const confirmar = window.confirm(
                'Tem certeza que deseja apagar este registro?'
            );

            if (!confirmar) return;

            const indice = usuarios.findIndex(item => item.id === id);

            if (indice !== -1) {
                usuarios.splice(indice, 1);
            }

            usuariosFiltrados = usuariosFiltrados.filter(item => item.id !== id);

            atualizarTabelaUsuarios();

            exibirMensagemUsuario(
                'Usuário excluído da demonstração!',
                'success'
            );
        }
    });
}
