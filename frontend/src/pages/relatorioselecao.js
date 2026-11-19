const categorias = [
    { id: 1, nome: 'Alimentação' },
    { id: 2, nome: 'Combustível' },
    { id: 3, nome: 'Energia elétrica' },
    { id: 4, nome: 'Internet' },
    { id: 5, nome: 'Salário' },
    { id: 6, nome: 'Água' }
];

// Dados demonstrativos
const movimentos = [
    { id: 1, data: '2026-11-01', descricao: 'Supermercado', categoria_id: 1, tipo: 'O', valor: 350 },
    { id: 2, data: '2026-11-03', descricao: 'Combustível', categoria_id: 2, tipo: 'O', valor: 200 },
    { id: 3, data: '2026-11-05', descricao: 'Conta de energia', categoria_id: 3, tipo: 'O', valor: 180 },
    { id: 4, data: '2026-11-08', descricao: 'Internet', categoria_id: 4, tipo: 'O', valor: 100 },
    { id: 5, data: '2026-11-10', descricao: 'Salário', categoria_id: 5, tipo: 'P', valor: 3500 },
    { id: 6, data: '2026-10-05', descricao: 'Supermercado', categoria_id: 1, tipo: 'O', valor: 320 },
    { id: 7, data: '2026-10-10', descricao: 'Conta de água', categoria_id: 6, tipo: 'O', valor: 85 },
    { id: 8, data: '2026-10-15', descricao: 'Salário', categoria_id: 5, tipo: 'P', valor: 3500 }
];

export function renderRelatorio() {
    return `
        <div class="container-fluid px-4">

            <div class="mb-1 hstack gap-3">
                <button type="button"
                    class="btn btn-info btn-sm px-1 py-0"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAjudaRelatorio"
                    title="Ajuda">
                    <i class="fa-solid fa-question"></i>
                </button>

                <h4 class="mt-3">Relatório seleção</h4>

                <ol class="breadcrumb mb-3 mt-3 ms-auto">
                    <li class="breadcrumb-item">
                        <a href="#/dashboard" class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">
                        Movimentos Relatório - Seleção
                    </li>
                </ol>
            </div>

            <div class="card mb-1 border-light shadow">
                <div class="card-header border border-light-subtle">
                    <span class="pesquisar">Pesquisar - Filtros:</span>
                </div>

                <div class="card-body">
                    <form id="formRelatorio">

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label for="relatorioDataDe" class="col-form-label">
                                    Movimento de
                                </label>
                            </div>

                            <div class="col-12 col-md-3">
                                <input type="date" class="form-control"
                                    name="data_de" id="relatorioDataDe">
                            </div>

                            <div class="col-12 col-md-1 text-md-center">
                                até
                            </div>

                            <div class="col-12 col-md-3">
                                <input type="date" class="form-control"
                                    name="data_ate" id="relatorioDataAte">
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label for="relatorioCategoria" class="col-form-label">
                                    Categoria
                                </label>
                            </div>

                            <div class="col-12 col-md-6">
                                <select name="categoria_id" id="relatorioCategoria"
                                    class="form-select">
                                    <option value="">Todas as categorias</option>
                                    ${categorias.map(item => `
                                        <option value="${item.id}">${item.nome}</option>
                                    `).join('')}
                                </select>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label class="col-form-label">Tipo</label>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="radio" name="tipo_filtro"
                                            id="relatorioObrigacao" value="O" checked>
                                        <label class="form-check-label"
                                            for="relatorioObrigacao">Obrigação</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="radio" name="tipo_filtro"
                                            id="relatorioPrevisao" value="P">
                                        <label class="form-check-label"
                                            for="relatorioPrevisao">Previsão</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label for="relatorioSintetico" class="col-form-label">
                                    Sintético
                                </label>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="OmitirData" value="1"
                                        id="relatorioSintetico">
                                    <label class="form-check-label"
                                        for="relatorioSintetico">
                                        Omitir detalhamento das datas
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label for="relatorioMesAno" class="col-form-label">
                                    Agrupar Mês/Ano
                                </label>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="mesAno" value="1"
                                        id="relatorioMesAno">
                                    <label class="form-check-label"
                                        for="relatorioMesAno">
                                        Agrupar movimentos por mês e ano
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-info btn-sm">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    Visualizar
                                </button>

                                <button type="reset" class="btn btn-warning btn-sm"
                                    id="btnLimparRelatorio">
                                    <i class="fa-solid fa-trash"></i>
                                    Limpar
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

            <div id="resultadoRelatorio" class="mt-4"></div>
            <div id="mensagemRelatorio" class="mt-3" aria-live="polite"></div>

        </div>

        <!-- Modal de ajuda -->
        <div class="modal fade" id="modalAjudaRelatorio"
            tabindex="-1" aria-labelledby="modalAjudaRelatorioLabel"
            aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAjudaRelatorioLabel"
                            style="color: #3fb4b8;">Ajuda</h5>

                        <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Fechar">
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="pesquisar mb-2">Relatório:</div>
                        <div>🏷️ Movimento de / até - apresentar os movimentos do período escolhido.</div>
                        <div>🏷️ Categoria - apresentar os movimentos da categoria.</div>
                        <div>🏷️ Tipo - apresentar os movimentos do tipo.</div>
                        <div>🏷️ Analítico - apresentar os movimentos sem agrupar.</div>
                        <div>🏷️ Sintético - omitir o detalhamento por datas.</div>
                        <div>🏷️ Agrupar Mês/Ano - apresentar movimentos agrupados por mês e ano.</div>
                        <div>🏷️ Visualizar - fazer a pesquisa com os filtros escolhidos.</div>
                        <div>🏷️ Limpar - limpar os campos preenchidos.</div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Fechar</button>
                    </div>

                </div>
            </div>
        </div>
    `;
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

function nomeCategoria(id) {
    return categorias.find(item => item.id === id)?.nome ?? 'Sem categoria';
}

function escaparHtml(valor) {
    return String(valor ?? '').replace(/[&<>"']/g, caractere => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    })[caractere]);
}

function renderTabelaRelatorio(lista, sintetico, agruparMesAno) {
    if (lista.length === 0) {
        return `
            <div class="alert alert-warning">
                Nenhum movimento encontrado para os filtros selecionados.
            </div>
        `;
    }

    let linhas = '';

    if (sintetico) {
        const grupos = {};

        lista.forEach(item => {
            const chave = item.categoria_id;
            if (!grupos[chave]) {
                grupos[chave] = { categoria: nomeCategoria(chave), total: 0 };
            }
            grupos[chave].total += item.valor;
        });

        linhas = Object.values(grupos).map(item => `
            <tr>
                <td>${escaparHtml(item.categoria)}</td>
                <td class="text-end">${formatarMoeda(item.total)}</td>
            </tr>
        `).join('');
    } else {
        let itens = [...lista];

        if (agruparMesAno) {
            itens.sort((a, b) => a.data.localeCompare(b.data));
            let mesAtual = '';

            linhas = itens.map(item => {
                const mes = item.data.substring(0, 7);
                let separador = '';

                if (mes !== mesAtual) {
                    mesAtual = mes;
                    const [ano, numeroMes] = mes.split('-');
                    const nomeMes = new Date(Number(ano), Number(numeroMes) - 1)
                        .toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });

                    separador = `
                        <tr class="table-secondary">
                            <th colspan="4">${nomeMes}</th>
                        </tr>
                    `;
                }

                return separador + `
                    <tr>
                        <td>${formatarData(item.data)}</td>
                        <td>${escaparHtml(item.descricao)}</td>
                        <td>${escaparHtml(nomeCategoria(item.categoria_id))}</td>
                        <td class="text-end">${formatarMoeda(item.valor)}</td>
                    </tr>
                `;
            }).join('');
        } else {
            linhas = itens.map(item => `
                <tr>
                    <td>${formatarData(item.data)}</td>
                    <td>${escaparHtml(item.descricao)}</td>
                    <td>${escaparHtml(nomeCategoria(item.categoria_id))}</td>
                    <td class="text-end">${formatarMoeda(item.valor)}</td>
                </tr>
            `).join('');
        }
    }

    const total = lista.reduce((soma, item) => soma + item.valor, 0);

    if (sintetico) {
        return `
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Categoria</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>${linhas}</tbody>
                    <tfoot>
                        <tr>
                            <th>Total geral</th>
                            <th class="text-end">${formatarMoeda(total)}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        `;
    }

    return `
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th class="text-end">Valor</th>
                    </tr>
                </thead>
                <tbody>${linhas}</tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">Total geral</th>
                        <th class="text-end">${formatarMoeda(total)}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    `;
}

export function initRelatorio() {
    const formulario = document.querySelector('#formRelatorio');
    if (!formulario) return;

    const resultado = document.querySelector('#resultadoRelatorio');
    const mensagem = document.querySelector('#mensagemRelatorio');

    formulario.addEventListener('submit', event => {
        event.preventDefault();

        const dataDe = document.querySelector('#relatorioDataDe').value;
        const dataAte = document.querySelector('#relatorioDataAte').value;
        const categoriaId = document.querySelector('#relatorioCategoria').value;
        const tipo = document.querySelector(
            'input[name="tipo_filtro"]:checked'
        ).value;

        const sintetico = document.querySelector('#relatorioSintetico').checked;
        const agruparMesAno = document.querySelector('#relatorioMesAno').checked;

        if (dataDe && dataAte && dataDe > dataAte) {
            mensagem.innerHTML = `
                <div class="alert alert-danger">
                    A data inicial não pode ser posterior à data final.
                </div>
            `;
            resultado.innerHTML = '';
            return;
        }

        const lista = movimentos.filter(item => {
            return (!dataDe || item.data >= dataDe) &&
                (!dataAte || item.data <= dataAte) &&
                (!categoriaId || item.categoria_id === Number(categoriaId)) &&
                item.tipo === tipo;
        });

        resultado.innerHTML = `
            <div class="card mb-4 border-light shadow">
                <div class="card-header">
                    <span>Resultado do relatório</span>
                </div>
                <div class="card-body">
                    ${renderTabelaRelatorio(lista, sintetico, agruparMesAno)}
                </div>
            </div>
        `;

        mensagem.innerHTML = `
            <div class="alert alert-info">
                Relatório demonstrativo: ${lista.length} movimento(s) encontrado(s).
            </div>
        `;
    });

    formulario.addEventListener('reset', () => {
        resultado.innerHTML = '';
        mensagem.innerHTML = '';
    });
}