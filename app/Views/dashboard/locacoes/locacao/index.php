<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>
<div class="content-wrapper">
    <div class="container mt-4">
        <?php if (session()->has('contrato_id')): ?>
            <script>
                window.onload = function() {
                    let contratoUrl = "<?= base_url('/locacoes/contrato/') ?>" + "<?= session('contrato_id') ?>";
                    window.open(contratoUrl, '_blank');
                };
            </script>
        <?php endif; ?>

        <h1>Locações</h1>
        <div class="card p-3">
            <div class="row g-2 align-items-end">
                <!-- <div class="col-md-2">
                    <label for="tipo" class="form-label search">Tipo</label>
                    <select id="tipo" class="form-control">
                        <option value="">Selecione</option>
                        <option value="1">Data</option>
                        <option value="2">Nome</option>
                        <option value="3">Razão Social</option>
                        <option value="4">Código</option>
                    </select>
                </div> -->
                <div class="col-md-6">
                    <label for="palavra" class="form-label search">Palavra-chave</label>
                    <input type="text" id="palavra" class="form-control" placeholder="Digite sua busca">
                </div>
                <div class="col-md-2">
                    <label for="situacao" class="form-label search">Situação</label>
                    <select id="situacao" class="form-control">
                        <option value="">Selecione</option>
                        <option value="1">Agendado</option>
                        <option value="2">Pendente</option>
                        <option value="3">Atrasado</option>
                        <option value="4">Finalizada</option>
                        <option value="5">Cancelado</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="button" id="buscar-btn" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
                </div>
                <div class="col-md-2">
                    <a href="<?= base_url('locacoes/cadastrar') ?>">
                        <button type="button" class="btn btn-success"><i class="fa-solid fa-pen"></i> Nova Locação</button>
                    </a>
                </div>
            </div>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Cód.</th>
                        <th>Data de Criação</th>
                        <th>Cliente</th>
                        <th>Período</th>
                        <th>Valor</th>
                        <th>Detalhe</th>
                        <th>Situação</th>
                        <th>Pagamento</th>
                        <th>Forma de Pagamento</th>
                    </tr>
                </thead>
                <tbody id="tabela-locacoes">
                    <?php foreach ($locacoes as $locacao): ?>
                        <tr>
                            <td><?= $locacao['id'] ?></td>
                            <td><?= date("d/m/Y H:i:s", strtotime($locacao['created_at'])) ?> <br></td>
                            <td><a href="<?= base_url() ?>locacoes/resumo/<?= $locacao['id'] ?>"><?= $locacao['cliente_nome'] ?></a></td>
                            <td>
                                <?= date("d/m/Y H:i:s", strtotime($locacao['data_entrega'])) ?> <br>
                                <?= date("d/m/Y H:i:s", strtotime($locacao['data_devolucao'])) ?>
                            </td>

                            <td><?= 'R$ ' . number_format($locacao['valor_total'], 2, ',', '.'); ?></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Mais
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" target="_blank" href="<?= base_url('locacoes/contrato/') . $locacao['id'] ?>">Emitir Contrato</a></li>
                                        <li><a class="dropdown-item" href="<?= site_url('locacoes/edita/' . $locacao['id'] . '?page=' . ($_GET['page'] ?? 1)) ?>">Editar Contrato</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('locacoes/cancelar/') . $locacao['id'] ?>">Cancelar Contrato</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('locacoes/anexos/') . $locacao['id'] . '?page=' . $paginacao->getCurrentPage() ?>">Anexar</a></li>
                                    </ul>
                                </div>
                            </td>
                            <?php
                            $situacao = (int) $locacao['situacao'];
                            ?>

                            <td>
                                <?php if ($situacao !== 5): ?>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-confirmar <?= $situacao === 4 ? 'btn-danger' : 'btn-info' ?>"
                                        data-id="<?= $locacao['id'] ?>"
                                        data-situacao="<?= $situacao ?>">

                                        <?php
                                        echo match ($situacao) {
                                            1 => 'Agendado',
                                            2 => 'Pendente',
                                            3 => 'Atrasado',
                                            4 => 'Finalizada',
                                        };
                                        ?>
                                    </button>
                                <?php else: ?>
                                    <span class="btn btn-warning btn-sm">Cancelado</span>
                                <?php endif; ?>
                            </td>


                            <td>
                                <?php if ($locacao['pagamento'] == 0): ?>
                                    <span class="btn btn-warning">Pendente</span>
                                <?php elseif ($locacao['pagamento'] == 1): ?>
                                    <span class="btn btn-success">Pago</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $locacao['forma_pagamento'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                <?php echo $paginacao->links('default', 'custom_pager') ?>
            </div>

        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const buscarBtn = document.getElementById("buscar-btn");
        const palavraInput = document.getElementById("palavra");
        const situacaoSelect = document.getElementById("situacao");
        const tabelaBody = document.getElementById("tabela-locacoes");

        const baseBuscarUrl = "<?= base_url('locacoes/buscar') ?>";
        const baseConfirmarUrl = "<?= base_url('locacoes/confirmar/') ?>";

        function buscarLocacoes() {
            salvarFiltros();
            tabelaBody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center">
                    Carregando... <i class="fas fa-spinner fa-spin"></i>
                </td>
            </tr>
        `;

            let queryParams = [];

            if (palavraInput.value.trim()) {
                queryParams.push(`palavra=${encodeURIComponent(palavraInput.value.trim())}`);
            }

            if (situacaoSelect.value) {
                queryParams.push(`situacao=${encodeURIComponent(situacaoSelect.value)}`);
            }

            const url = queryParams.length ?
                `${baseBuscarUrl}?${queryParams.join('&')}` :
                baseBuscarUrl;

            fetch(url)
                .then(res => res.json())
                .then(data => {

                    tabelaBody.innerHTML = "";

                    if (!data || data.length === 0) {
                        tabelaBody.innerHTML = `
                        <tr>
                            <td colspan="9" class="text-center">
                                Nenhuma locação encontrada
                            </td>
                        </tr>
                    `;
                        return;
                    }

                    const rows = data.map(locacao => {

                        const situacao = parseInt(locacao.situacao, 10);

                        /* ===== STATUS / BOTÃO ===== */
                        let statusLabel = '';
                        let statusClass = '';

                        switch (situacao) {
                            case 1:
                                statusLabel = 'Agendado';
                                statusClass = 'btn-info';
                                break;
                            case 2:
                                statusLabel = 'Pendente';
                                statusClass = 'btn-warning';
                                break;
                            case 3:
                                statusLabel = 'Atrasado';
                                statusClass = 'btn-warning';
                                break;
                            case 4:
                                statusLabel = 'Finalizada';
                                statusClass = 'btn-danger';
                                break;
                            case 5:
                                statusLabel = 'Cancelado';
                                statusClass = 'btn-warning';
                                break;
                        }

                        let statusHtml = '';

                        if (situacao !== 5) {
                            statusHtml = `
                            <button
                                type="button"
                                class="btn btn-sm btn-confirmar ${statusClass}"
                                data-id="${locacao.id}"
                                data-situacao="${situacao}">
                                ${statusLabel}
                            </button>
                        `;
                        } else {
                            statusHtml = `
                            <span class="btn btn-sm btn-warning">
                                Cancelado
                            </span>
                        `;
                        }

                        /* ===== PAGAMENTO ===== */
                        const pagamento = locacao.pagamento == 1 ?
                            `<span class="btn btn-success btn-sm">Pago</span>` :
                            `<span class="btn btn-warning btn-sm">Pendente</span>`;

                        return `
                        <tr>
                            <td>${locacao.id}</td>
                            <td>${locacao.created_at}</td>
                            <td>
                                <a href="locacoes/resumo/${locacao.id}">
                                    ${locacao.cliente_nome || locacao.cliente_razao_social}
                                </a>
                            </td>
                            <td>
                                ${locacao.data_entrega}<br>
                                ${locacao.data_devolucao}
                            </td>
                            <td>
                                ${parseFloat(locacao.valor_total)
                                    .toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button"
                                        class="btn btn-primary btn-sm dropdown-toggle"
                                        data-bs-toggle="dropdown">
                                        Mais
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" target="_blank"
                                               href="<?= base_url('locacoes/contrato/') ?>${locacao.id}">
                                                Emitir Contrato
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                               href="<?= base_url('locacoes/edita/') ?>${locacao.id}">
                                                Editar Contrato
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                               href="<?= base_url('locacoes/cancelar/') ?>${locacao.id}">
                                                Cancelar Contrato
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                               href="<?= base_url('locacoes/anexos/') ?>${locacao.id}">
                                                Anexar
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td>${statusHtml}</td>
                            <td>${pagamento}</td>
                            <td>${locacao.forma_pagamento || ''}</td>
                        </tr>
                    `;
                    }).join('');

                    tabelaBody.innerHTML = rows;
                })
                .catch(error => {
                    tabelaBody.innerHTML = `
                    <tr>
                        <td colspan="9" class="text-center text-danger">
                            Erro: ${error.message}
                        </td>
                    </tr>
                `;
                });
        }

        function salvarFiltros() {
            localStorage.setItem('locacoes_filtro', JSON.stringify({
                palavra: palavraInput.value,
                situacao: situacaoSelect.value
            }));
        }

        /* ===== EVENTOS DE BUSCA ===== */
        buscarBtn?.addEventListener("click", buscarLocacoes);

        palavraInput?.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                buscarLocacoes();
            }
        });

        /* ===== CONFIRMAÇÃO VIA AJAX (DELEGAÇÃO) ===== */
        document.addEventListener("click", function(e) {

            if (!e.target.classList.contains("btn-confirmar")) return;

            const button = e.target;
            const id = button.dataset.id;

            const textoOriginal = button.innerText;
            button.disabled = true;
            button.innerText = "...";

            fetch(`${baseConfirmarUrl}${id}`, {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(res => res.json())
                .then(data => {

                    if (!data.success) {
                        alert(data.message || "Erro ao atualizar");
                        button.innerText = textoOriginal;
                        return;
                    }

                    if (data.situacao == 4) {
                        button.classList.remove("btn-info", "btn-warning");
                        button.classList.add("btn-danger");
                        button.innerText = "Finalizada";
                    } else {
                        button.classList.remove("btn-danger");
                        button.classList.add("btn-info");
                        button.innerText = "Agendado";
                    }

                    button.dataset.situacao = data.situacao;
                })
                .catch(() => {
                    alert("Erro ao atualizar locação");
                    button.innerText = textoOriginal;
                })
                .finally(() => {
                    button.disabled = false;
                });
        });

        function restaurarFiltros() {
            const filtros = localStorage.getItem('locacoes_filtro');

            if (!filtros) return;

            const dados = JSON.parse(filtros);

            if (dados.palavra) {
                palavraInput.value = dados.palavra;
            }

            if (dados.situacao) {
                situacaoSelect.value = dados.situacao;
            }

            // Se quiser que a busca rode automaticamente ao voltar:
            buscarLocacoes();
        }
        restaurarFiltros();

    });
</script>

<?= $this->endSection(); ?>