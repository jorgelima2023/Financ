
const formasPagamento = [
    { id: 1, nome: 'Pix' },
    { id: 2, nome: 'Débito' },
    { id: 3, nome: 'Crédito' },
    { id: 4, nome: 'Transferência' }
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

function renderLinhasFormas() {
    if (formasPagamento.length === 0) {
        return `
            <tr>
                <td colspan="3" class="text-center text-muted py-4">
                    Nenhuma Forma de Pagamento encontrada!
                </td>
            </tr>
        `;
    }

    return formasPagamento.map(forma => `
        <tr data-id="${forma.id}">
            <td>
                <input type="hidden"
                       class="forma-id"
                       value="${forma.id}">
            </td>

            <td>
                <input type="text"
                       class="form-control form-control-sm forma-nome"
                       maxlength="60"
                       value="${escapeHtml(forma.nome)}"
                       title="Forma de Pagamento: ${forma.id}">
            </td>

            <td class="text-center">
                <input type="checkbox"
                       class="form-check-input forma-excluir"
                       title="Se marcado, o registro será excluído!">
            </td>
        </tr>
    `).join('');
}

export function renderFormaPagamentos() {
    return `
        <div class="container-fluid px-4">

            <div class="mb-1 hstack gap-2">
                <h2 class="mt-3">Formas Pagamento - formulário</h2>

                <ol class="breadcrumb mb-3 mt-3 ms-auto">
                    <li class="breadcrumb-item">
                        <a href="#/dashboard"
                           class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Formas Pagamento
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
                                    <th style="min-width: 300px;">Nome</th>
                                    <th style="width: 100px;"
                                        class="text-center">Excluir</th>
                                </tr>
                            </thead>

                            <tbody id="tbodyFormasPagamento">
                                ${renderLinhasFormas()}
                            </tbody>

                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <small class="text-muted" id="totalFormasPagamento">
                            ${formasPagamento.length} formas de pagamento
                        </small>

                        <button type="button"
                                class="btn btn-warning btn-sm"
                                id="btnEditarFormasPagamento">
                            <i class="fas fa-save me-1"></i>
                            Editar
                        </button>

                    </div>

                    <div id="mensagemFormasPagamento"
                         class="mt-3"
                         aria-live="polite"></div>

                </div>
            </div>
        </div>
    `;
}

export function initFormaPagamentos() {
    const tabela = document.querySelector('#tbodyFormasPagamento');
    const botaoEditar = document.querySelector('#btnEditarFormasPagamento');
    const mensagem = document.querySelector('#mensagemFormasPagamento');

    if (!tabela || !botaoEditar) return;

    tabela.addEventListener('change', event => {
        if (event.target.classList.contains('forma-excluir')) {
            event.target.closest('tr').classList.toggle(
                'table-danger',
                event.target.checked
            );
        }
    });

    botaoEditar.addEventListener('click', () => {
        const linhas = [...tabela.querySelectorAll('tr[data-id]')];

        linhas.forEach(linha => {
            const id = Number(linha.querySelector('.forma-id').value);

            const indice = formasPagamento.findIndex(
                forma => forma.id === id
            );

            if (indice === -1) return;

            if (linha.querySelector('.forma-excluir').checked) {
                formasPagamento.splice(indice, 1);
                return;
            }

            formasPagamento[indice].nome =
                linha.querySelector('.forma-nome').value.trim();
        });

        tabela.innerHTML = renderLinhasFormas();

        document.querySelector('#totalFormasPagamento').textContent =
            `${formasPagamento.length} formas de pagamento`;

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
