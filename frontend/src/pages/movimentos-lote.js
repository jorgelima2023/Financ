
const categorias = [
    { id: 1, nome: 'Alimentação' },
    { id: 2, nome: 'Combustível' },
    { id: 3, nome: 'Energia elétrica' },
    { id: 4, nome: 'Internet' },
    { id: 5, nome: 'Salário' },
    { id: 6, nome: 'Água' }
];

const contas = [
    { id: 1, nome: 'Banco Bradesco' },
    { id: 2, nome: 'Banco Nubank' },
    { id: 3, nome: 'Dinheiro' }
];

const formasPagamento = [
    { id: 1, nome: 'Pix' },
    { id: 2, nome: 'Débito' },
    { id: 3, nome: 'Crédito' },
    { id: 4, nome: 'Transferência' }
];

let movimentosGerados = [];

export function renderMovimentosLote() {
    return `
        <div class="container-fluid px-4">

            <div class="mb-1 hstack gap-3">
                <button type="button"
                    class="btn btn-info btn-sm px-1 py-0"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAjudaLote"
                    title="Ajuda">
                    <i class="fa-solid fa-question"></i>
                </button>

                <h2 class="mt-3">Lançamentos em lote</h2>

                <ol class="breadcrumb mb-3 mt-3 ms-auto">
                    <li class="breadcrumb-item">
                        <a href="#/dashboard" class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Movimentos - Gerar Lançamentos em lote
                    </li>
                </ol>
            </div>

            <div class="card mb-1 border-light shadow">
                <div class="card-header border border-light-subtle">
                    <span class="pesquisar">Seleção:</span>
                </div>

                <div class="card-body fs-6">

                    <form id="formMovimentosLote">

                        <input type="hidden" name="indexGerarLctos"
                            id="indexGerarLctos" value="1">

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label for="descricaoLote" class="col-form-label">
                                    Descrição
                                </label>
                            </div>
                            <div class="col-12 col-md-6">
                                <input type="text"
                                    class="form-control"
                                    placeholder="Descrição do movimento"
                                    name="descricao"
                                    id="descricaoLote"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label for="dataInicialLote" class="col-form-label">
                                    Vencimento inicial
                                </label>
                            </div>
                            <div class="col-12 col-md-3">
                                <input type="date"
                                    class="form-control"
                                    name="data_de"
                                    id="dataInicialLote"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label class="col-form-label">Tipo</label>
                            </div>
                            <div class="col-12 col-md-5">
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="tipo_filtro"
                                            id="tipoObrigacao"
                                            value="O"
                                            checked>
                                        <label class="form-check-label" for="tipoObrigacao">
                                            Obrigação
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="tipo_filtro"
                                            id="tipoPrevisao"
                                            value="P">
                                        <label class="form-check-label" for="tipoPrevisao">
                                            Previsão
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label class="col-form-label">Tipo Lcto</label>
                            </div>
                            <div class="col-12 col-md-5">
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="tipo_lcto"
                                            id="tipoReceita"
                                            value="1"
                                            required>
                                        <label class="form-check-label" for="tipoReceita">
                                            Receita
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="tipo_lcto"
                                            id="tipoDespesa"
                                            value="0"
                                            checked
                                            required>
                                        <label class="form-check-label" for="tipoDespesa">
                                            Despesa
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label for="categoriaLote" class="col-form-label">
                                    Categoria
                                </label>
                            </div>
                            <div class="col-12 col-md-6">
                                <select name="categoria_id"
                                    id="categoriaLote"
                                    class="form-select"
                                    required>
                                    <option value="">Selecione</option>
                                    ${categorias.map(item => `
                                        <option value="${item.id}">
                                            ${item.nome}
                                        </option>
                                    `).join('')}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label for="contaLote" class="col-form-label">
                                    Conta
                                </label>
                            </div>
                            <div class="col-12 col-md-5">
                                <select name="contabusca"
                                    id="contaLote"
                                    class="form-select"
                                    required>
                                    <option value="">Selecione</option>
                                    ${contas.map(item => `
                                        <option value="${item.id}">
                                            ${item.nome}
                                        </option>
                                    `).join('')}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label for="formaPagamentoLote" class="col-form-label">
                                    Forma Pgto
                                </label>
                            </div>
                            <div class="col-12 col-md-5">
                                <select name="eforma_pgto_id"
                                    id="formaPagamentoLote"
                                    class="form-select"
                                    required>
                                    <option value="">Selecione</option>
                                    ${formasPagamento.map(item => `
                                        <option value="${item.id}">
                                            ${item.nome}
                                        </option>
                                    `).join('')}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label for="valorParcelaLote" class="col-form-label">
                                    Valor Parcela
                                </label>
                            </div>
                            <div class="col-12 col-md-2">
                                <input type="text"
                                    name="valor"
                                    class="form-control"
                                    id="valorParcelaLote"
                                    placeholder="0,00"
                                    inputmode="decimal"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label for="quantidadeLote" class="col-form-label">
                                    Quantidade Parc.
                                </label>
                            </div>
                            <div class="col-12 col-md-2">
                                <input type="number"
                                    name="qtde"
                                    class="form-control"
                                    id="quantidadeLote"
                                    min="1"
                                    max="120"
                                    value="1"
                                    required>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12 col-md-4">
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    Gerar
                                </button>

                                <button type="reset"
                                    id="btnLimparLote"
                                    class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-trash"></i>
                                    Limpar
                                </button>
                            </div>
                        </div>

                    </form>

                    <div id="mensagemLote" class="mt-3" aria-live="polite"></div>

                </div>
            </div>

            <div id="resultadoLote"></div>

        </div>

        <!-- Modal de ajuda -->
        <div class="modal fade" id="modalAjudaLote"
            tabindex="-1" aria-labelledby="modalAjudaLoteLabel"
            aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAjudaLoteLabel"
                            style="color: #3fb4b8;">
                            Ajuda
                        </h5>

                        <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Fechar">
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="pesquisar mb-2">Lançamentos em lote:</div>

                        <div>🏷️ Seleção - Preencher os campos:</div>
                        <div>🏷️ Descrição - A descrição do lançamento.</div>
                        <div>🏷️ Vencimento inicial - Data inicial de vencimento do primeiro lançamento.</div>
                        <div>🏷️ Valor parcela - Informar o valor da parcela a ser gerada.</div>
                        <div>🏷️ Quantidade Parc. - Quantidade de parcelas a ser gerada.</div>
                        <div>🏷️ Gerar - Faz a geração dos movimentos conforme a quantidade e valor de parcelas.</div>
                        <div>🏷️ Limpar - Limpa os campos preenchidos.</div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Fechar
                        </button>
                    </div>

                </div>
            </div>
        </div>
    `;
}

function converterValor(valor) {
    let texto = String(valor).trim();

    if (texto.includes(',')) {
        texto = texto.replace(/\./g, '').replace(',', '.');
    }

    return Number(texto);
}

function formatarMoeda(valor) {
    return Number(valor).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    });
}

function formatarData(data) {
    const [ano, mes, dia] = data.split('-');
    return `${dia}/${mes}/${ano}`;
}

function adicionarMeses(dataInicial, quantidadeMeses) {
    const [ano, mes, dia] = dataInicial.split('-').map(Number);

    const primeiroDia = new Date(ano, mes - 1 + quantidadeMeses, 1);
    const ultimoDia = new Date(
        primeiroDia.getFullYear(),
        primeiroDia.getMonth() + 1,
        0
    ).getDate();

    const diaFinal = Math.min(dia, ultimoDia);

    const anoFinal = primeiroDia.getFullYear();
    const mesFinal = String(primeiroDia.getMonth() + 1).padStart(2, '0');
    const diaFormatado = String(diaFinal).padStart(2, '0');

    return `${anoFinal}-${mesFinal}-${diaFormatado}`;
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

function renderResultadoLote() {
    if (movimentosGerados.length === 0) return '';

    const total = movimentosGerados.reduce(
        (soma, movimento) => soma + movimento.valor,
        0
    );

    return `
        <div class="card mb-4 border-light shadow">
            <div class="card-header hstack gap-2">
                <span>Prévia dos lançamentos</span>
                <span class="ms-auto badge bg-primary">
                    ${movimentosGerados.length} parcelas
                </span>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Parcela</th>
                                <th>Vencimento</th>
                                <th>Descrição</th>
                                <th class="text-end">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${movimentosGerados.map(item => `
                                <tr>
                                    <td>${item.parcela}</td>
                                    <td>${formatarData(item.data)}</td>
                                    <td>${escapeHtml(item.descricao)}</td>
                                    <td class="text-end">${formatarMoeda(item.valor)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th class="text-end">${formatarMoeda(total)}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="alert alert-info mb-0">
                    Esta é apenas uma prévia demonstrativa.
                    Nenhum movimento foi gravado no banco de dados.
                </div>
            </div>
        </div>
    `;
}

export function initMovimentosLote() {
    const formulario = document.querySelector('#formMovimentosLote');

    if (!formulario) return;

    const mensagem = document.querySelector('#mensagemLote');
    const resultado = document.querySelector('#resultadoLote');

    formulario.addEventListener('submit', event => {
        event.preventDefault();

        const descricao = document.querySelector('#descricaoLote').value.trim();
        const dataInicial = document.querySelector('#dataInicialLote').value;
        const valor = converterValor(
            document.querySelector('#valorParcelaLote').value
        );
        const quantidade = Number(
            document.querySelector('#quantidadeLote').value
        );

        if (!Number.isFinite(valor) || valor <= 0) {
            mensagem.innerHTML = `
                <div class="alert alert-danger">
                    Informe um valor de parcela válido.
                </div>
            `;
            return;
        }

        if (!Number.isInteger(quantidade) || quantidade < 1 || quantidade > 120) {
            mensagem.innerHTML = `
                <div class="alert alert-danger">
                    Informe uma quantidade entre 1 e 120 parcelas.
                </div>
            `;
            return;
        }

        const categoria = categorias.find(item =>
            item.id === Number(document.querySelector('#categoriaLote').value)
        );

        const conta = contas.find(item =>
            item.id === Number(document.querySelector('#contaLote').value)
        );

        const forma = formasPagamento.find(item =>
            item.id === Number(document.querySelector('#formaPagamentoLote').value)
        );

        const tipo = document.querySelector(
            'input[name="tipo_filtro"]:checked'
        ).value;

        const tipoLcto = document.querySelector(
            'input[name="tipo_lcto"]:checked'
        ).value;

        movimentosGerados = Array.from({ length: quantidade }, (_, indice) => ({
            parcela: `${indice + 1}/${quantidade}`,
            data: adicionarMeses(dataInicial, indice),
            descricao,
            valor,
            categoria: categoria.nome,
            conta: conta.nome,
            forma: forma.nome,
            tipo,
            tipoLcto
        }));

        resultado.innerHTML = renderResultadoLote();

        mensagem.innerHTML = `
            <div class="alert alert-success">
                Prévia gerada com sucesso: ${quantidade} parcelas.
            </div>
        `;
    });

    formulario.addEventListener('reset', () => {
        movimentosGerados = [];

        mensagem.innerHTML = '';
        resultado.innerHTML = '';
    });
}
