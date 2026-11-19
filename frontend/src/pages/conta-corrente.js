
const contasCorrentes = [
    {
        id: 1,
        nome: 'Banco Bradesco',
        agencia: '',
        conta: '',
        cod_compe: '',
        carteira: ''
    },
    {
        id: 2,
        nome: 'Banco Nubank',
        agencia: '',
        conta: '',
        cod_compe: '',
        carteira: ''
    },
    {
        id: 3,
        nome: 'Dinheiro',
        agencia: '',
        conta: '',
        cod_compe: '',
        carteira: ''
    }
];

function escapeHtml(valor) {
    return String(valor ?? '').replace(/[&<>"']/g, caractere => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    })[caractere]);
}

function renderLinhasContas() {
    if (contasCorrentes.length === 0) {
        return `
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    Nenhuma Conta Corrente encontrada!
                </td>
            </tr>
        `;
    }

    return contasCorrentes.map(conta => `
        <tr data-id="${conta.id}">
            <td>
                <input type="hidden"
                       class="conta-id"
                       value="${conta.id}">
            </td>

            <td>
                <input type="text"
                       class="form-control form-control-sm conta-nome"
                       maxlength="60"
                       value="${escapeHtml(conta.nome)}"
                       title="Conta Corrente: ${conta.id}">
            </td>

            <td>
                <input type="text"
                       class="form-control form-control-sm conta-agencia"
                       maxlength="10"
                       value="${escapeHtml(conta.agencia)}">
            </td>

            <td>
                <input type="text"
                       class="form-control form-control-sm conta-numero"
                       maxlength="10"
                       value="${escapeHtml(conta.conta)}">
            </td>

            <td>
                <input type="text"
                       class="form-control form-control-sm conta-compe"
                       maxlength="10"
                       value="${escapeHtml(conta.cod_compe)}">
            </td>

            <td>
                <input type="text"
                       class="form-control form-control-sm conta-carteira"
                       maxlength="10"
                       value="${escapeHtml(conta.carteira)}">
            </td>

            <td class="text-center">
                <input type="checkbox"
                       class="form-check-input conta-excluir"
                       title="Se marcado, o registro será excluído!">
            </td>
        </tr>
    `).join('');
}

export function renderContaCorrente() {
    return `
        <div class="container-fluid px-4">

            <div class="mb-1 hstack gap-2">
                <h2 class="mt-3">Contas Correntes - formulário</h2>

                <ol class="breadcrumb mb-3 mt-3 ms-auto">
                    <li class="breadcrumb-item">
                        <a href="#/dashboard"
                           class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Contas Correntes
                    </li>
                </ol>
            </div>

            <div class="card mb-5 border-light shadow">
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th style="width: 35px;"></th>
                                    <th style="min-width: 220px;">Nome</th>
                                    <th style="min-width: 110px;">Agência</th>
                                    <th style="min-width: 120px;">Conta</th>
                                    <th style="min-width: 120px;">Cod. COMPE</th>
                                    <th style="min-width: 110px;">Carteira</th>
                                    <th style="width: 90px;"
                                        class="text-center">Excluir</th>
                                </tr>
                            </thead>

                            <tbody id="tbodyContasCorrentes">
                                ${renderLinhasContas()}
                            </tbody>

                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <small class="text-muted" id="totalContasCorrentes">
                            ${contasCorrentes.length} contas
                        </small>

                        <button type="button"
                                class="btn btn-warning btn-sm"
                                id="btnEditarContasCorrentes">
                            <i class="fas fa-save me-1"></i>
                            Editar
                        </button>

                    </div>

                    <div id="mensagemContasCorrentes"
                         class="mt-3"
                         aria-live="polite"></div>

                </div>
            </div>
        </div>
    `;
}

export function initContaCorrente() {
    const tabela = document.querySelector('#tbodyContasCorrentes');
    const botaoEditar = document.querySelector('#btnEditarContasCorrentes');
    const mensagem = document.querySelector('#mensagemContasCorrentes');

    if (!tabela || !botaoEditar) return;

    // Destaca as contas marcadas para exclusão
    tabela.addEventListener('change', event => {
        if (event.target.classList.contains('conta-excluir')) {
            event.target.closest('tr').classList.toggle(
                'table-danger',
                event.target.checked
            );
        }
    });

    // Aplica as alterações na demonstração
    botaoEditar.addEventListener('click', () => {
        const linhas = [...tabela.querySelectorAll('tr[data-id]')];

        linhas.forEach(linha => {
            const id = Number(linha.querySelector('.conta-id').value);

            const indice = contasCorrentes.findIndex(
                conta => conta.id === id
            );

            if (indice === -1) return;

            if (linha.querySelector('.conta-excluir').checked) {
                contasCorrentes.splice(indice, 1);
                return;
            }

            contasCorrentes[indice].nome =
                linha.querySelector('.conta-nome').value.trim();

            contasCorrentes[indice].agencia =
                linha.querySelector('.conta-agencia').value.trim();

            contasCorrentes[indice].conta =
                linha.querySelector('.conta-numero').value.trim();

            contasCorrentes[indice].cod_compe =
                linha.querySelector('.conta-compe').value.trim();

            contasCorrentes[indice].carteira =
                linha.querySelector('.conta-carteira').value.trim();
        });

        tabela.innerHTML = renderLinhasContas();

        document.querySelector('#totalContasCorrentes').textContent =
            `${contasCorrentes.length} contas`;

        mensagem.innerHTML = `
            <div class="alert alert-success alert-dismissible fade show"
                 role="alert">
                Alterações aplicadas na demonstração!
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Fechar"></button>
            </div>
        `;
    });
}
