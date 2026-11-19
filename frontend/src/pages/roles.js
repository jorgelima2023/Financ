
const roles = [
    { id: 1, nome: 'Administrador' },
    { id: 2, nome: 'Usuário' },
    { id: 3, nome: 'Financeiro' }
];

export function renderRoles() {
    return `
        <div class="container-fluid px-4">

            <div class="mb-1 hstack gap-2">
                <h2 class="mt-3">Papel</h2>

                <ol class="breadcrumb mb-3 mt-3 ms-auto">
                    <li class="breadcrumb-item">
                        <a href="#/dashboard"
                           class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Papéis</li>
                </ol>
            </div>

            <div class="card mb-4 border-light shadow">

                <div class="card-header hstack gap-2">
                    <span>Listar</span>

                    <span class="ms-auto">
                        <button type="button"
                                class="btn btn-success btn-sm"
                                id="btnCadastrarRole">
                            <i class="fa-regular fa-square-plus"></i>
                            Cadastrar
                        </button>
                    </span>
                </div>

                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th class="d-none d-sm-table-cell"
                                        style="width: 80px;">ID</th>
                                    <th>Nome</th>
                                    <th class="text-center"
                                        style="min-width: 300px;">Ações</th>
                                </tr>
                            </thead>

                            <tbody id="tbodyRoles">
                                ${renderLinhasRoles()}
                            </tbody>

                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <small class="text-muted" id="totalRoles">
                            ${roles.length} papéis
                        </small>

                        <nav aria-label="Paginação de papéis">
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

                    <div id="mensagemRoles"
                         class="mt-3"
                         aria-live="polite"></div>

                </div>
            </div>
        </div>
    `;
}

function renderLinhasRoles() {
    if (roles.length === 0) {
        return `
            <tr>
                <td colspan="3" class="text-center text-muted py-4">
                    Nenhum papel encontrado!
                </td>
            </tr>
        `;
    }

    return roles.map(role => `
        <tr data-id="${role.id}">

            <th class="d-none d-sm-table-cell">
                ${role.id}
            </th>

            <td>
                ${escapeHtml(role.nome)}
            </td>

            <td>
                <div class="d-flex flex-row flex-wrap justify-content-center gap-1">

                    <button type="button"
                            class="btn btn-info btn-sm btn-permissoes-role"
                            data-id="${role.id}">
                        <i class="fa-solid fa-list"></i>
                        Permissões
                    </button>

                    <button type="button"
                            class="btn btn-warning btn-sm btn-editar-role"
                            data-id="${role.id}">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Editar
                    </button>

                    <button type="button"
                            class="btn btn-danger btn-sm btn-apagar-role"
                            data-id="${role.id}">
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

export function initRoles() {
    const tabela = document.querySelector('#tbodyRoles');
    const mensagem = document.querySelector('#mensagemRoles');

    if (!tabela) return;

    document.querySelector('#btnCadastrarRole')
        ?.addEventListener('click', () => {
            mensagem.innerHTML = `
                <div class="alert alert-info">
                    Demonstrativo, a tela de cadastro de papéis não será apresentada.
                </div>
            `;
        });

    tabela.addEventListener('click', event => {
        const botao = event.target.closest('button');

        if (!botao) return;

        const id = Number(botao.dataset.id);
        const role = roles.find(item => item.id === id);

        if (!role) return;

        if (botao.classList.contains('btn-permissoes-role')) {
            mensagem.innerHTML = `
                <div class="alert alert-info">
                    Gerenciamento de permissões do papel
                    <strong>${escapeHtml(role.nome)}</strong>
                    demonstrativo, não será apresentada.
                </div>
            `;
        }

        if (botao.classList.contains('btn-editar-role')) {
            mensagem.innerHTML = `
                <div class="alert alert-info">
                    Edição do papel
                    <strong>${escapeHtml(role.nome)}</strong>
                    demonstrativo, não será apresentada.
                </div>
            `;
        }

        if (botao.classList.contains('btn-apagar-role')) {
            const confirmar = window.confirm(
                'Tem certeza que deseja apagar este registro?'
            );

            if (!confirmar) return;

            const indice = roles.findIndex(item => item.id === id);

            if (indice !== -1) {
                roles.splice(indice, 1);
            }

            tabela.innerHTML = renderLinhasRoles();

            document.querySelector('#totalRoles').textContent =
                `${roles.length} papéis`;

            mensagem.innerHTML = `
                <div class="alert alert-success">
                    Papel excluído da demonstração!
                </div>
            `;
        }
    });
}
