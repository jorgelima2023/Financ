
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

export function renderMovimentosImportar() {
    return `
        <div class="container-fluid px-4">

            <div class="mb-1 hstack gap-3">
                <button type="button"
                    class="btn btn-info btn-sm px-1 py-0"
                    data-bs-toggle="modal"
                    data-bs-target="#modalAjudaImportar"
                    title="Ajuda">
                    <i class="fa-solid fa-question"></i>
                </button>

                <h2 class="mt-3">Movimento importar</h2>

                <ol class="breadcrumb mb-3 mt-3 ms-auto">
                    <li class="breadcrumb-item">
                        <a href="#/dashboard" class="text-decoration-none">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Movimentos</li>
                </ol>
            </div>

            <!-- Importação CSV -->
            <div class="card mb-2 border-light shadow">
                <div class="card-header border border-light-subtle">
                    <span class="pesquisar">Filtros - CSV:</span>
                </div>

                <div class="card-body fs-6">

                    <form id="formImportacaoCsv">

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
                                            id="importTipoObrigacao"
                                            value="O">
                                        <label class="form-check-label"
                                            for="importTipoObrigacao">
                                            Obrigação
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="tipo_filtro"
                                            id="importTipoPrevisao"
                                            value="P">
                                        <label class="form-check-label"
                                            for="importTipoPrevisao">
                                            Previsão
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-2">
                                <label for="importCategoria" class="col-form-label">
                                    Categoria
                                </label>
                            </div>
                            <div class="col-12 col-md-6">
                                <select name="categoria_id"
                                    id="importCategoria"
                                    class="form-select">
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
                                <label for="importConta" class="col-form-label">
                                    Conta
                                </label>
                            </div>
                            <div class="col-12 col-md-5">
                                <select name="contabusca"
                                    id="importConta"
                                    class="form-select">
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
                                <label for="importFormaPagamento" class="col-form-label">
                                    Forma Pgto
                                </label>
                            </div>
                            <div class="col-12 col-md-5">
                                <select name="forma_pgto_id"
                                    id="importFormaPagamento"
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
                                <label for="importDataLancamento" class="col-form-label">
                                    Data lançamento Movimento
                                </label>
                            </div>
                            <div class="col-12 col-md-3">
                                <input type="date"
                                    class="form-control"
                                    name="data_de"
                                    id="importDataLancamento">
                            </div>
                        </div>

                        <div class="row mb-2 align-items-center">
                            <div class="col-12 col-md-5">
                                <div class="card mb-2 mt-2 pt-1 border-light shadow">
                                    <div class="mb-1 hstack gap-1">
                                        <input type="file"
                                            name="file"
                                            id="arquivoCsv"
                                            class="form-control form-control-sm"
                                            accept=".csv,text/csv"
                                            title=".csv"
                                            required>

                                        <button type="submit"
                                            class="btn btn-outline-secondary btn-sm px-2"
                                            id="btnImportarCsv">
                                            CSV
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                    <div id="mensagemCsv" class="mt-2" aria-live="polite"></div>
                    <div id="resultadoCsv"></div>

                </div>
            </div>

            <!-- Importação OFX -->
            <div class="card mb-2 mt-2 pt-1 border-light shadow">
                <div class="card-body">
                    <form id="formImportacaoOfx">
                        <div class="mb-1 hstack gap-2">
                            <input type="file"
                                name="file"
                                id="arquivoOfx"
                                class="form-control form-control-sm"
                                accept=".ofx,application/x-ofx"
                                title=".ofx"
                                required>

                            <button type="submit"
                                class="btn btn-outline-secondary btn-sm px-2">
                                OFX
                            </button>
                        </div>
                    </form>

                    <div id="mensagemOfx" class="mt-2" aria-live="polite"></div>
                    <div id="resultadoOfx"></div>
                </div>
            </div>

        </div>

        <!-- Modal de ajuda -->
        <div class="modal fade" id="modalAjudaImportar"
            tabindex="-1"
            aria-labelledby="modalAjudaImportarLabel"
            aria-hidden="true">

            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title"
                            id="modalAjudaImportarLabel"
                            style="color: #3fb4b8;">
                            Ajuda
                        </h5>

                        <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Fechar">
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="pesquisar mb-2">
                            Movimentos importar extrato/arquivo (.csv):
                        </div>

                        <div>📢 Arquivo do tipo (.csv) - Filtros CSV - Preencher os campos</div>
                        <div>Tipo - tipo do lançamento.</div>
                        <div>Categoria - informar uma categoria.</div>
                        <div>Conta corrente - informar uma conta.</div>
                        <div>Forma Pgto - informar uma forma.</div>
                        <div>Deve exportar arquivo formato Excel e salvar no formato CSV.</div>
                        <div>Delimitador (;), decimal (.) e separador de campos nenhum.</div>
                        <div class="mt-2">🏷️ Exemplo de cabeçalho CSV:</div>
                        <code>Data;Historico;Valor(US$);Valor(R$)</code>
                        <div class="mt-1">24/06;SEGURO SUPERPROTEGIDO;0.00;9.99</div>
                        <div class="mt-2">
                            🏷️ Os registros serão validados por data + histórico + valor.
                        </div>

                        <hr>

                        <div class="pesquisar mb-2">
                            Movimentos importar extrato/arquivo (.ofx):
                        </div>

                        <div>📢 Escolher arquivo - selecionar o arquivo (.ofx).</div>
                        <div>Botão OFX - faz a importação e abre um pré-form de confirmação.</div>
                        <div>🏷️ Os registros serão validados pelo campo documento no arquivo.</div>

                    </div>

                    <div class="modal-footer">
                        <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                            Fechar
                        </button>
                    </div>

                </div>
            </div>
        </div>
    `;
}

// Lê CSV separado por ponto e vírgula, respeitando campos entre aspas.
function separarLinhaCsv(linha) {
    const campos = [];
    let campo = '';
    let dentroAspas = false;

    for (let i = 0; i < linha.length; i++) {
        const caractere = linha[i];

        if (caractere === '"') {
            if (dentroAspas && linha[i + 1] === '"') {
                campo += '"';
                i++;
            } else {
                dentroAspas = !dentroAspas;
            }
        } else if (caractere === ';' && !dentroAspas) {
            campos.push(campo.trim());
            campo = '';
        } else {
            campo += caractere;
        }
    }

    campos.push(campo.trim());
    return campos;
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

function exibirMensagemImportacao(elemento, mensagem, tipo = 'info') {
    elemento.innerHTML = `
        <div class="alert alert-${tipo}" role="alert">
            ${mensagem}
        </div>
    `;
}

function renderTabelaCsv(registros) {
    if (!registros.length) {
        return `
            <div class="alert alert-warning">
                O arquivo não contém registros para exibir.
            </div>
        `;
    }

    return `
        <div class="card mt-3 border-light shadow">
            <div class="card-header">
                Prévia do arquivo CSV
                <span class="badge bg-primary ms-2">
                    ${registros.length} registros
                </span>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Data</th>
                                <th>Histórico</th>
                                <th>Valor (US$)</th>
                                <th>Valor (R$)</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${registros.map(registro => `
                                <tr>
                                    <td>${escaparHtml(registro.data)}</td>
                                    <td>${escaparHtml(registro.historico)}</td>
                                    <td class="text-end">${escaparHtml(registro.valorUsd)}</td>
                                    <td class="text-end">${escaparHtml(registro.valorReal)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mb-0">
                    Prévia demonstrativa. Os registros ainda não foram importados
                    nem comparados com os movimentos do banco de dados.
                </div>
            </div>
        </div>
    `;
}

function lerCsv(conteudo) {
    const linhas = conteudo
        .replace(/^\uFEFF/, '')
        .split(/\r?\n/)
        .filter(linha => linha.trim() !== '');

    if (linhas.length < 2) {
        throw new Error('O CSV não possui linhas de dados.');
    }

    const cabecalho = separarLinhaCsv(linhas[0])
        .map(item => item.trim().toLowerCase());

    const indiceData = cabecalho.indexOf('data');
    const indiceHistorico = cabecalho.indexOf('historico');
    const indiceUsd = cabecalho.indexOf('valor(us$)');
    const indiceReal = cabecalho.indexOf('valor(r$)');

    if ([indiceData, indiceHistorico, indiceUsd, indiceReal].some(i => i === -1)) {
        throw new Error(
            'Cabeçalho inválido. Esperado: Data;Historico;Valor(US$);Valor(R$).'
        );
    }

    return linhas.slice(1).map(linha => {
        const campos = separarLinhaCsv(linha);

        return {
            data: campos[indiceData] ?? '',
            historico: campos[indiceHistorico] ?? '',
            valorUsd: campos[indiceUsd] ?? '',
            valorReal: campos[indiceReal] ?? ''
        };
    });
}

function extrairCampoOfx(bloco, tag) {
    const expressao = new RegExp(
        `<${tag}>([^<\\r\\n]+)`,
        'i'
    );

    return bloco.match(expressao)?.[1]?.trim() ?? '';
}

function formatarDataOfx(valor) {
    if (!valor) return '';

    const data = valor.substring(0, 8);

    if (!/^\d{8}$/.test(data)) return valor;

    return `${data.substring(6, 8)}/${data.substring(4, 6)}/${data.substring(0, 4)}`;
}

function lerOfx(conteudo) {
    const blocos = conteudo.match(/<STMTTRN>[\s\S]*?<\/STMTTRN>/gi) || [];

    return blocos.map(bloco => ({
        documento: extrairCampoOfx(bloco, 'FITID'),
        data: formatarDataOfx(extrairCampoOfx(bloco, 'DTPOSTED')),
        tipo: extrairCampoOfx(bloco, 'TRNTYPE'),
        valor: extrairCampoOfx(bloco, 'TRNAMT'),
        historico:
            extrairCampoOfx(bloco, 'NAME') ||
            extrairCampoOfx(bloco, 'MEMO')
    }));
}

function renderTabelaOfx(registros) {
    if (!registros.length) {
        return `
            <div class="alert alert-warning">
                Não foram encontrados lançamentos no arquivo OFX.
                Verifique se o arquivo contém registros STMTTRN.
            </div>
        `;
    }

    return `
        <div class="card mt-3 border-light shadow">
            <div class="card-header">
                Prévia do arquivo OFX
                <span class="badge bg-primary ms-2">
                    ${registros.length} registros
                </span>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Documento</th>
                                <th>Data</th>
                                <th>Tipo</th>
                                <th>Histórico</th>
                                <th class="text-end">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${registros.map(registro => `
                                <tr>
                                    <td>${escaparHtml(registro.documento)}</td>
                                    <td>${escaparHtml(registro.data)}</td>
                                    <td>${escaparHtml(registro.tipo)}</td>
                                    <td>${escaparHtml(registro.historico)}</td>
                                    <td class="text-end">${escaparHtml(registro.valor)}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mb-0">
                    Prévia demonstrativa. A confirmação e a importação definitiva
                    ainda dependem da integração com o Laravel.
                </div>
            </div>
        </div>
    `;
}

export function initMovimentosImportar() {
    const formCsv = document.querySelector('#formImportacaoCsv');
    const formOfx = document.querySelector('#formImportacaoOfx');

    if (!formCsv || !formOfx) return;

    const mensagemCsv = document.querySelector('#mensagemCsv');
    const resultadoCsv = document.querySelector('#resultadoCsv');

    const mensagemOfx = document.querySelector('#mensagemOfx');
    const resultadoOfx = document.querySelector('#resultadoOfx');

    // Importação CSV
    formCsv.addEventListener('submit', async event => {
        event.preventDefault();

        const arquivo = document.querySelector('#arquivoCsv').files[0];

        if (!arquivo) {
            exibirMensagemImportacao(
                mensagemCsv,
                'Selecione um arquivo CSV.',
                'warning'
            );
            return;
        }

        if (!arquivo.name.toLowerCase().endsWith('.csv')) {
            exibirMensagemImportacao(
                mensagemCsv,
                'Selecione um arquivo com extensão .csv.',
                'danger'
            );
            return;
        }

        try {
            const conteudo = await arquivo.text();
            const registros = lerCsv(conteudo);

            resultadoCsv.innerHTML = renderTabelaCsv(registros);

            exibirMensagemImportacao(
                mensagemCsv,
                `Arquivo ${escaparHtml(arquivo.name)} lido com sucesso.`,
                'success'
            );
        } catch (erro) {
            resultadoCsv.innerHTML = '';

            exibirMensagemImportacao(
                mensagemCsv,
                escaparHtml(erro.message || 'Não foi possível ler o arquivo CSV.'),
                'danger'
            );
        }
    });

    // Importação OFX
    formOfx.addEventListener('submit', async event => {
        event.preventDefault();

        const arquivo = document.querySelector('#arquivoOfx').files[0];

        if (!arquivo) {
            exibirMensagemImportacao(
                mensagemOfx,
                'Selecione um arquivo OFX.',
                'warning'
            );
            return;
        }

        if (!arquivo.name.toLowerCase().endsWith('.ofx')) {
            exibirMensagemImportacao(
                mensagemOfx,
                'Selecione um arquivo com extensão .ofx.',
                'danger'
            );
            return;
        }

        try {
            const conteudo = await arquivo.text();
            const registros = lerOfx(conteudo);

            resultadoOfx.innerHTML = renderTabelaOfx(registros);

            exibirMensagemImportacao(
                mensagemOfx,
                `Arquivo ${escaparHtml(arquivo.name)} lido com sucesso.`,
                'success'
            );
        } catch (erro) {
            resultadoOfx.innerHTML = '';

            exibirMensagemImportacao(
                mensagemOfx,
                escaparHtml(erro.message || 'Não foi possível ler o arquivo OFX.'),
                'danger'
            );
        }
    });
}
