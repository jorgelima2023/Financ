import Chart from 'chart.js/auto';
import { Modal } from 'bootstrap';

const categorias = [
    { id: 1, nome: 'Alimentação', classif: '1.1.1' },
    { id: 2, nome: 'Combustível', classif: '1.1.2' },
    { id: 3, nome: 'Energia elétrica', classif: '1.1.3' },
    { id: 4, nome: 'Internet', classif: '1.1.4' },
    { id: 5, nome: 'Salário', classif: '2.1.1' },
    { id: 6, nome: 'Água', classif: '1.1.5' },
];

const contas = [
    { id: 1, nome: 'Banco Bradesco' },
    { id: 2, nome: 'Banco Nubank' },
    { id: 3, nome: 'Dinheiro' },
];

const formasPgto = [
    { id: 1, nome: 'Pix' },
    { id: 2, nome: 'Débito' },
    { id: 3, nome: 'Crédito' },
    { id: 4, nome: 'Transferência' },
];

let movimentos = [
    { id: 1, dt: '2026-11-01', tipo_lc: 0, tipo: 'O', categoria_id: 1, conta_id: 3, forma_pgto_id: 2, descricao: 'Compras supermercado', dcto: 'NF 1021', valor: 485.70 },
    { id: 2, dt: '2026-11-02', tipo_lc: 0, tipo: 'O', categoria_id: 2, conta_id: 1, forma_pgto_id: 1, descricao: 'Abastecimento veículo', dcto: 'POSTO 45', valor: 220.00 },
    { id: 3, dt: '2026-11-03', tipo_lc: 0, tipo: 'O', categoria_id: 3, conta_id: 1, forma_pgto_id: 4, descricao: 'Conta de energia Copel', dcto: 'FAT 11/26', valor: 198.35 },
    { id: 4, dt: '2026-11-04', tipo_lc: 1, tipo: 'O', categoria_id: 5, conta_id: 1, forma_pgto_id: 4, descricao: 'Salário mensal', dcto: 'SAL 11/26', valor: 4200.00 },
    { id: 5, dt: '2026-11-05', tipo_lc: 0, tipo: 'P', categoria_id: 4, conta_id: 2, forma_pgto_id: 1, descricao: 'Internet fibra', dcto: 'FAT 8821', valor: 119.90 },
    { id: 6, dt: '2026-11-06', tipo_lc: 0, tipo: 'O', categoria_id: 6, conta_id: 1, forma_pgto_id: 1, descricao: 'Conta de água', dcto: 'SANEPAR', valor: 92.40 },
    { id: 7, dt: '2026-11-07', tipo_lc: 0, tipo: 'O', categoria_id: 1, conta_id: 2, forma_pgto_id: 3, descricao: 'Restaurante', dcto: 'CUPOM 778', valor: 86.50 },
    { id: 8, dt: '2026-11-08', tipo_lc: 0, tipo: 'P', categoria_id: 2, conta_id: 1, forma_pgto_id: 2, descricao: 'Combustível previsto', dcto: 'PREV 08', valor: 250.00 },
    { id: 9, dt: '2026-11-08', tipo_lc: 1, tipo: 'O', categoria_id: 5, conta_id: 1, forma_pgto_id: 4, descricao: 'Receita extra', dcto: 'REC 008', valor: 650.00 },
    { id: 10, dt: '2026-11-08', tipo_lc: 0, tipo: 'O', categoria_id: 4, conta_id: 2, forma_pgto_id: 1, descricao: 'Plano de internet', dcto: 'FAT 8890', valor: 109.90 },
];

let chartInstance = null;

const moeda = valor => Number(valor).toLocaleString('pt-BR', {
    style: 'currency',
    currency: 'BRL',
});

const dataBR = data => {
    if (!data) return '';
    const [ano, mes, dia] = data.substring(0, 10).split('-');
    return `${dia}/${mes}/${ano}`;
};

const nomeCategoria = id => categorias.find(item => item.id === Number(id))?.nome ?? '';
const classifCategoria = id => categorias.find(item => item.id === Number(id))?.classif ?? '';
const nomeConta = id => contas.find(item => item.id === Number(id))?.nome ?? '';
const nomeForma = id => formasPgto.find(item => item.id === Number(id))?.nome ?? '';

const escapeHtml = value => String(value ?? '').replace(/[&<>"']/g, char => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;',
}[char]));

const options = (lista, selecionado = '') => lista.map(item =>
    `<option value="${item.id}" ${String(item.id) === String(selecionado) ? 'selected' : ''}>${escapeHtml(item.nome)}</option>`
).join('');

export function renderMovimentos() {
    return `
    <div class="container-fluid px-4">
        <div class="mb-2 d-flex align-items-center">
            <h2 class="mt-3">Movimentos</h2>
            <nav aria-label="breadcrumb" class="ms-auto">
                <ol class="breadcrumb mb-0 mt-3">
                    <li class="breadcrumb-item">
                        <a href="#/dashboard" class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Movimentos</li>
                </ol>
            </nav>
        </div>

        <div class="card mb-2 border-light shadow-sm">
            <div class="card-header">
                <strong>Pesquisar - Filtros</strong>
            </div>

            <div class="card-body">
                <form id="formFiltrosMovimentos">
                    <div class="row g-2 align-items-end">

                        <div class="col-lg-5 col-md-6">
                            <label for="fDescricao" class="form-label">Descrição</label>
                            <input type="search" id="fDescricao"
                                class="form-control form-control-sm"
                                placeholder="Descrição do movimento (qualquer posição)">
                        </div>

                        <div class="col-lg-2 col-md-3">
                            <label for="fDataDe" class="form-label">Cadastro de</label>
                            <input type="date" id="fDataDe"
                                class="form-control form-control-sm"
                                value="2026-11-01">
                        </div>

                        <div class="col-lg-2 col-md-3">
                            <label for="fDataAte" class="form-label">Até</label>
                            <input type="date" id="fDataAte"
                                class="form-control form-control-sm"
                                value="2026-11-30">
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label d-block">Tipo</label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                    name="fTipo" id="fTipoO" value="O" checked>
                                <label class="form-check-label" for="fTipoO">
                                    Obrigação
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                    name="fTipo" id="fTipoP" value="P">
                                <label class="form-check-label" for="fTipoP">
                                    Previsão
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                    name="fTipo" id="fTipoTodos" value="">
                                <label class="form-check-label" for="fTipoTodos">
                                    Todos
                                </label>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6">
                            <label for="fCategoria" class="form-label">Categoria</label>
                            <select id="fCategoria" class="form-select form-select-sm">
                                <option value="">Todas as categorias</option>
                                ${options(categorias)}
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="fConta" class="form-label">Conta</label>
                            <select id="fConta" class="form-select form-select-sm"
                                multiple size="3">
                                ${options(contas)}
                            </select>
                            <small class="text-muted">
                                Use Ctrl para selecionar mais de uma.
                            </small>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label for="fForma" class="form-label">
                                Forma de pagamento
                            </label>
                            <select id="fForma" class="form-select form-select-sm">
                                <option value="">Todas</option>
                                ${options(formasPgto)}
                            </select>
                        </div>

                        <div class="col-lg-2 d-flex gap-1">
                            <button type="submit"
                                class="btn btn-info btn-sm text-white">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Pesquisar
                            </button>

                            <button type="button" id="btnLimparFiltros"
                                class="btn btn-warning btn-sm">
                                <i class="fa-solid fa-eraser"></i>
                                Limpar
                            </button>
                        </div>

                    </div>

                    <div class="small fst-italic text-muted mt-2"
                        id="resumoFiltros"></div>
                </form>
            </div>
        </div>

        <div class="card mb-2 border-light shadow-sm">
            <div class="card-body py-2 d-flex flex-wrap align-items-center gap-2">

                <button type="button" class="btn btn-success btn-sm"
                    id="btnNovoMovimento">
                    <i class="fa-solid fa-plus"></i> Cadastrar
                </button>

                <span class="text-muted small ms-2">Gráficos:</span>

                <button type="button"
                    class="btn btn-outline-secondary btn-sm"
                    id="btnGraficoBar">
                    <i class="fa-solid fa-chart-column"></i>
                    Bar-chart
                </button>

                <button type="button"
                    class="btn btn-outline-secondary btn-sm"
                    id="btnGraficoDoughnut">
                    <i class="fa-solid fa-chart-pie"></i>
                    Pie-chart
                </button>

            </div>
        </div>

        <div class="card mb-4 border-light shadow-sm">
            <div class="card-header">
                <strong>Listar movimentos</strong>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover align-middle fs-7">
                        <thead class="table-light">
                            <tr>
                                <th>Excl.</th>
                                <th>Edit.</th>
                                <th>Tipo</th>
                                <th>Classif.</th>
                                <th>Data</th>
                                <th>Descrição</th>
                                <th>Dcto</th>
                                <th>Conta</th>
                                <th>Categoria</th>
                                <th>Forma Pgto</th>
                                <th class="text-end">Valor</th>
                            </tr>
                        </thead>

                        <tbody id="tbodyMovimentos"></tbody>

                        <tfoot class="table-light">
                            <tr>
                                <th colspan="10" class="text-end">
                                    Total exibido:
                                </th>
                                <th class="text-end" id="totalMovimentos">
                                    R$ 0,00
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="small text-muted" id="quantidadeMovimentos"></div>
            </div>
        </div>
    </div>
        <div class="modal fade" id="modalMovimento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">

            <form class="modal-content" id="formMovimento">

                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalMovimento">
                        Cadastrar Movimento
                    </h5>

                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Fechar">
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="movimentoId">

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label for="mData" class="form-label">Data</label>
                            <input type="date" id="mData"
                                class="form-control" required>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label d-block">
                                Tipo lançamento
                            </label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                    name="mTipoLc" id="mReceita"
                                    value="1" required>
                                <label class="form-check-label" for="mReceita">
                                    Receita
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                    name="mTipoLc" id="mDespesa" value="0">
                                <label class="form-check-label" for="mDespesa">
                                    Despesa
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label d-block">Tipo</label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                    name="mTipo" id="mObrigacao"
                                    value="O" required>
                                <label class="form-check-label" for="mObrigacao">
                                    Obrigação
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio"
                                    name="mTipo" id="mPrevisao" value="P">
                                <label class="form-check-label" for="mPrevisao">
                                    Previsão
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="mCategoria" class="form-label">
                                Categoria
                            </label>

                            <select id="mCategoria" class="form-select" required>
                                <option value="">Selecione uma categoria</option>
                                ${options(categorias)}
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="mConta" class="form-label">Conta</label>

                            <select id="mConta" class="form-select" required>
                                <option value="">Selecione uma conta</option>
                                ${options(contas)}
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="mForma" class="form-label">
                                Forma de pagamento
                            </label>

                            <select id="mForma" class="form-select" required>
                                <option value="">Selecione uma forma</option>
                                ${options(formasPgto)}
                            </select>
                        </div>

                        <div class="col-md-8">
                            <label for="mDescricao" class="form-label">
                                Descrição
                            </label>

                            <input type="text" id="mDescricao"
                                class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label for="mDcto" class="form-label">Dcto</label>
                            <input type="text" id="mDcto" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label for="mValor" class="form-label">
                                Valor (R$)
                            </label>

                            <input type="number" id="mValor"
                                class="form-control"
                                min="0.01" step="0.01" required>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Fechar
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Salvar
                    </button>
                </div>

            </form>
        </div>
    </div>

    <div class="modal fade" id="modalGrafico" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Gráfico dos movimentos filtrados
                    </h5>

                    <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Fechar">
                    </button>
                </div>

                <div class="modal-body">
                    <canvas id="canvasMovimentos" height="120"></canvas>
                </div>

            </div>
        </div>
    </div>
    `;
}

export function initMovimentos() {

    const tbody = document.querySelector('#tbodyMovimentos');

    if (!tbody) return;

    const modalMovimento = new Modal(
        document.querySelector('#modalMovimento')
    );

    const modalGrafico = new Modal(
        document.querySelector('#modalGrafico')
    );

    const filtrados = () => {

        const descricao = document.querySelector('#fDescricao')
            .value.trim().toLowerCase();

        const dataDe = document.querySelector('#fDataDe').value;
        const dataAte = document.querySelector('#fDataAte').value;

        const tipo = document.querySelector(
            'input[name="fTipo"]:checked'
        )?.value ?? '';

        const categoria = document.querySelector('#fCategoria').value;

        const contasSelecionadas = Array.from(
            document.querySelector('#fConta').selectedOptions
        ).map(o => Number(o.value));

        const forma = document.querySelector('#fForma').value;

        return movimentos.filter(m => {

            return (!descricao || m.descricao.toLowerCase().includes(descricao))
                && (!dataDe || m.dt >= dataDe)
                && (!dataAte || m.dt <= dataAte)
                && (!tipo || m.tipo === tipo)
                && (!categoria || m.categoria_id === Number(categoria))
                && (!contasSelecionadas.length || contasSelecionadas.includes(m.conta_id))
                && (!forma || m.forma_pgto_id === Number(forma));

        }).sort((a, b) => b.dt.localeCompare(a.dt) || b.id - a.id);
    };

    const atualizarTabela = () => {

        const lista = filtrados();

        tbody.innerHTML = lista.map(m => `
            <tr>
                <td>
                    <button type="button"
                        class="btn btn-outline-danger btn-sm py-0 px-1"
                        data-acao="excluir" data-id="${m.id}"
                        title="Excluir">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>

                <td>
                    <button type="button"
                        class="btn btn-outline-primary btn-sm py-0 px-1"
                        data-acao="editar" data-id="${m.id}"
                        title="Editar">
                        <i class="fa fa-pen"></i>
                    </button>
                </td>

                <td>${m.tipo_lc == 1 ? 'Receita' : 'Despesa'}</td>
                <td>${escapeHtml(classifCategoria(m.categoria_id))}</td>
                <td>${dataBR(m.dt)}</td>
                <td>${escapeHtml(m.descricao)}</td>
                <td>${escapeHtml(m.dcto)}</td>
                <td>${escapeHtml(nomeConta(m.conta_id))}</td>
                <td>${escapeHtml(nomeCategoria(m.categoria_id))}</td>
                <td>${escapeHtml(nomeForma(m.forma_pgto_id))}</td>
                <td class="text-end">${moeda(m.valor)}</td>
            </tr>
        `).join('');

        const total = lista.reduce(
            (soma, m) => soma + (Number(m.valor) || 0), 0
        );

        document.querySelector('#totalMovimentos').textContent = moeda(total);

        document.querySelector('#quantidadeMovimentos').textContent =
            `${lista.length} movimento(s) encontrado(s).`;

        document.querySelector('#resumoFiltros').textContent =
            `Exibindo ${lista.length} movimento(s) conforme os filtros informados.`;
    };

    const limparFormulario = () => {

        document.querySelector('#formMovimento').reset();
        document.querySelector('#movimentoId').value = '';
    };

    const abrirCadastro = movimento => {

        limparFormulario();

        document.querySelector('#tituloModalMovimento').textContent =
            movimento ? 'Editar Movimento' : 'Cadastrar Movimento';

        if (movimento) {

            document.querySelector('#movimentoId').value = movimento.id;
            document.querySelector('#mData').value = movimento.dt;

            document.querySelector(
                `#m${movimento.tipo_lc == 1 ? 'Receita' : 'Despesa'}`
            ).checked = true;

            document.querySelector(
                `#m${movimento.tipo === 'O' ? 'Obrigacao' : 'Previsao'}`
            ).checked = true;

            document.querySelector('#mCategoria').value =
                movimento.categoria_id;

            document.querySelector('#mConta').value =
                movimento.conta_id;

            document.querySelector('#mForma').value =
                movimento.forma_pgto_id;

            document.querySelector('#mDescricao').value =
                movimento.descricao;

            document.querySelector('#mDcto').value =
                movimento.dcto;

            document.querySelector('#mValor').value =
                movimento.valor;

        } else {

            document.querySelector('#mData').value =
                new Date().toISOString().slice(0, 10);

            document.querySelector('#mDespesa').checked = true;
            document.querySelector('#mObrigacao').checked = true;
        }

        modalMovimento.show();
    };
        document.querySelector('#formFiltrosMovimentos')
        .addEventListener('submit', event => {

            event.preventDefault();
            atualizarTabela();

        });

    document.querySelector('#btnLimparFiltros')
        .addEventListener('click', () => {

            document.querySelector('#formFiltrosMovimentos').reset();

            document.querySelector('#fDataDe').value = '';
            document.querySelector('#fDataAte').value = '';

            atualizarTabela();

        });

    document.querySelector('#btnNovoMovimento')
        .addEventListener('click', () => abrirCadastro());

    document.querySelector('#formMovimento')
        .addEventListener('submit', event => {

            event.preventDefault();

            const id = Number(
                document.querySelector('#movimentoId').value
            );

            const registro = {

                id: id || Math.max(
                    0, ...movimentos.map(m => m.id)
                ) + 1,

                dt: document.querySelector('#mData').value,

                tipo_lc: Number(
                    document.querySelector(
                        'input[name="mTipoLc"]:checked'
                    ).value
                ),

                tipo: document.querySelector(
                    'input[name="mTipo"]:checked'
                ).value,

                categoria_id: Number(
                    document.querySelector('#mCategoria').value
                ),

                conta_id: Number(
                    document.querySelector('#mConta').value
                ),

                forma_pgto_id: Number(
                    document.querySelector('#mForma').value
                ),

                descricao: document.querySelector('#mDescricao')
                    .value.trim(),

                dcto: document.querySelector('#mDcto')
                    .value.trim(),

                valor: Number(
                    document.querySelector('#mValor').value
                ),
            };

            if (id) {

                movimentos = movimentos.map(m =>
                    m.id === id ? registro : m
                );

            } else {

                movimentos.push(registro);

            }

            modalMovimento.hide();

            atualizarTabela();

        });

    tbody.addEventListener('click', event => {

        const botao = event.target.closest('button[data-acao]');

        if (!botao) return;

        const id = Number(botao.dataset.id);

        const movimento = movimentos.find(m => m.id === id);

        if (!movimento) return;

        if (botao.dataset.acao === 'editar') {

            abrirCadastro(movimento);

        } else if (botao.dataset.acao === 'excluir') {

            if (window.confirm(
                `Deseja excluir o movimento "${movimento.descricao}"?`
            )) {

                movimentos = movimentos.filter(m => m.id !== id);

                atualizarTabela();
            }
        }
    });

    const abrirGrafico = tipoGrafico => {

        const lista = filtrados();

        const agrupado = {};

        lista.forEach(m => {

            const categoria =
                nomeCategoria(m.categoria_id) || 'Sem categoria';

            agrupado[categoria] =
                (agrupado[categoria] || 0) + Number(m.valor);

        });

        if (chartInstance) {
            chartInstance.destroy();
        }

        modalGrafico.show();

        requestAnimationFrame(() => {

            const canvas = document.querySelector('#canvasMovimentos');

            chartInstance = new Chart(canvas, {

                type: tipoGrafico,

                data: {

                    labels: Object.keys(agrupado),

                    datasets: [{
                        label: 'Valor (R$)',
                        data: Object.values(agrupado),
                        borderWidth: 1,
                    }],
                },

                options: {

                    responsive: true,

                    plugins: {
                        legend: {
                            position: tipoGrafico === 'bar'
                                ? 'top'
                                : 'right',
                        },
                    },

                    ...(tipoGrafico === 'bar'
                        ? {
                            scales: {
                                y: {
                                    beginAtZero: true,
                                },
                            },
                        }
                        : {}),
                },
            });

        });
    };

    document.querySelector('#btnGraficoBar')
        .addEventListener('click', () => abrirGrafico('bar'));

    document.querySelector('#btnGraficoDoughnut')
        .addEventListener('click', () => abrirGrafico('doughnut'));

    document.querySelector('#modalGrafico')
        .addEventListener('hidden.bs.modal', () => {

            if (chartInstance) {

                chartInstance.destroy();
                chartInstance = null;

            }

        });

    atualizarTabela();
}