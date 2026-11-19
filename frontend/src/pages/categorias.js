
const categorias = [
    { id: 1, nome: 'Alimentação', classif: '1.1.1', soma: true, lcto: true, ativo: true },
    { id: 2, nome: 'Combustível', classif: '1.1.2', soma: true, lcto: true, ativo: true },
    { id: 3, nome: 'Energia elétrica', classif: '1.1.3', soma: true, lcto: true, ativo: true },
    { id: 4, nome: 'Internet', classif: '1.1.4', soma: true, lcto: true, ativo: true },
    { id: 5, nome: 'Salário', classif: '2.1.1', soma: true, lcto: true, ativo: true },
    { id: 6, nome: 'Água', classif: '1.1.5', soma: true, lcto: true, ativo: true }
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

function renderLinhasCategorias() {
    if (categorias.length === 0) {
        return `
            <tr>
                <td colspan="7" class="text-center text-muted py-4">
                    Nenhuma categoria encontrada!
                </td>
            </tr>
        `;
    }

    return categorias.map((categoria, i) => `
        <tr class="linha-item" data-index="${i}">
            <td>
                <input type="hidden"
                       class="categoria-id"
                       value="${categoria.id}">
            </td>

            <td>
                <input type="text"
                       class="form-control form-control-sm categoria-nome"
                       maxlength="60"
                       value="${escapeHtml(categoria.nome)}">
            </td>

            <td>
                <input type="text"
                       class="form-control form-control-sm categoria-classif"
                       maxlength="20"
                       value="${escapeHtml(categoria.classif)}">
            </td>

            <td class="text-center">
                <input type="checkbox"
                       class="form-check-input categoria-soma"
                       ${categoria.soma ? 'checked' : ''}>
            </td>

            <td class="text-center">
                <input type="checkbox"
                       class="form-check-input categoria-lcto"
                       ${categoria.lcto ? 'checked' : ''}>
            </td>

            <td class="text-center">
                <input type="checkbox"
                       class="form-check-input categoria-ativo"
                       ${categoria.ativo ? 'checked' : ''}>
            </td>

            <td class="text-center">
                <input type="checkbox"
                       class="form-check-input categoria-excluir">
            </td>
        </tr>
    `).join('');
}

export function renderCategorias() {
    return `
        <div class="container-fluid px-4">

            <div class="mb-1 hstack gap-2">
                <h2 class="mt-3">Categoria formulário</h2>

                <ol class="breadcrumb mb-3 mt-3 ms-auto">
                    <li class="breadcrumb-item">
                        <a href="#/dashboard"
                           class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Categorias</li>
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
                                    <th style="min-width: 150px;">Classificação</th>
                                    <th class="text-center">Somar</th>
                                    <th class="text-center">Lcto</th>
                                    <th class="text-center">Ativo</th>
                                    <th class="text-center">Excluir</th>
                                </tr>
                            </thead>

                            <tbody id="tbodyCategorias">
                                ${renderLinhasCategorias()}
                            </tbody>

                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">

                        <small class="text-muted" id="totalCategorias">
                            ${categorias.length} categorias
                        </small>

                        <button type="button"
                                class="btn btn-warning btn-sm"
                                id="btnEditarCategorias">
                            <i class="fas fa-save me-1"></i>
                            Editar
                        </button>

                    </div>

                    <div id="mensagemCategorias"
                         class="mt-3"
                         aria-live="polite"></div>

                </div>
            </div>
        </div>
    `;
}

export function initCategorias() {
    const tabela = document.querySelector('#tbodyCategorias');
    const botaoEditar = document.querySelector('#btnEditarCategorias');
    const mensagem = document.querySelector('#mensagemCategorias');

    if (!tabela || !botaoEditar) return;

    // Destaca as linhas marcadas para exclusão
    tabela.addEventListener('change', event => {
        if (event.target.classList.contains('categoria-excluir')) {
            const linha = event.target.closest('tr');

            linha.classList.toggle(
                'table-danger',
                event.target.checked
            );
        }
    });

    // Atualiza os dados demonstrativos em memória
    botaoEditar.addEventListener('click', () => {
        const linhas = [...tabela.querySelectorAll('tr[data-index]')];

        linhas.forEach(linha => {
            const id = Number(linha.querySelector('.categoria-id').value);

            const indice = categorias.findIndex(
                categoria => categoria.id === id
            );

            if (indice === -1) return;

            const excluir = linha.querySelector('.categoria-excluir').checked;

            if (excluir) {
                categorias.splice(indice, 1);
                return;
            }

            categorias[indice].nome =
                linha.querySelector('.categoria-nome').value.trim();

            categorias[indice].classif =
                linha.querySelector('.categoria-classif').value.trim();

            categorias[indice].soma =
                linha.querySelector('.categoria-soma').checked;

            categorias[indice].lcto =
                linha.querySelector('.categoria-lcto').checked;

            categorias[indice].ativo =
                linha.querySelector('.categoria-ativo').checked;
        });

        tabela.innerHTML = renderLinhasCategorias();

        document.querySelector('#totalCategorias').textContent =
            `${categorias.length} categorias`;

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
