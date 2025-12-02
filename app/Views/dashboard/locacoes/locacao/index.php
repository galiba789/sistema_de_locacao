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

                            <td>R$ <?= $locacao['valor_total'] ?></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Mais
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" target="_blank" href="<?= base_url('locacoes/contrato/') . $locacao['id'] ?>">Emitir Contrato</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('locacoes/edita/') . $locacao['id'] ?>">Editar Contrato</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('locacoes/cancelar/') . $locacao['id'] ?>">Cancelar Contrato</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('locacoes/anexos/') . $locacao['id'] ?>">Anexar</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <?php if ($locacao['situacao'] == 1): ?>
                                    <a href="<?= base_url('locacoes/confirmar/') . $locacao['id'] ?>" class="btn btn-info">Agendado</a>
                                <?php elseif ($locacao['situacao'] == 2): ?>
                                    <a href="<?= base_url('locacoes/confirmar/') . $locacao['id'] ?>" class="btn btn-info">Pendente</a>
                                <?php elseif ($locacao['situacao'] == 3): ?>
                                    <a href="<?= base_url('locacoes/confirmar/') . $locacao['id'] ?>" class="btn btn-info">Atrasado</a>
                                <?php elseif ($locacao['situacao'] == 4): ?>
                                   <a href="<?= base_url('locacoes/confirmar/') . $locacao['id'] ?>" class="btn btn-danger"> Finalizada</a>
                                <?php elseif ($locacao['situacao'] == 5): ?>
                                    <span class="btn btn-warning">Cancelado</span>
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
        const tipoSelect = document.getElementById("tipo");
        const situacaoSelect = document.getElementById("situacao");
        const tabelaBody = document.getElementById("tabela-locacoes");

        function buscarLocacoes() {

            tabelaBody.innerHTML = "<tr><td colspan='9' class='text-center'>Carregando... <i class='fas fa-spinner fa-spin'></i></td></tr>";

            const baseUrl = "<?= base_url('locacoes/buscar') ?>";
            const basePagamentoUrl = "<?= base_url('locacoes/pagamento/') ?>";
            let queryParams = [];

            // Buscar sempre pelo texto digitado
            if (palavraInput.value.trim()) {
                queryParams.push(`palavra=${encodeURIComponent(palavraInput.value.trim())}`);
            }

            // Situação continua opcional
            if (situacaoSelect.value) {
                queryParams.push(`situacao=${encodeURIComponent(situacaoSelect.value)}`);
            }

            const url = queryParams.length > 0 ? `${baseUrl}?${queryParams.join('&')}` : baseUrl;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    tabelaBody.innerHTML = "";

                    if (!data || data.length === 0) {
                        tabelaBody.innerHTML = "<tr><td colspan='9' class='text-center'>Nenhuma locação encontrada</td></tr>";
                        return;
                    }

                    let rows = data.map(locacao => {
                        let badgeHtml = "";
                        switch (parseInt(locacao.situacao)) {
                            case 1:
                                badgeHtml = '<span class="btn btn-info">Agendado</span>';
                                break;
                            case 2:
                                badgeHtml = '<span class="btn btn-warning">Pendente</span>';
                                break;
                            case 3:
                                badgeHtml = '<span class="btn btn-warning">Atrasado</span>';
                                break;
                            case 4:
                                badgeHtml = '<span class="btn btn-danger">Finalizada</span>';
                                break;
                            case 5:
                                badgeHtml = '<span class="btn btn-danger">Cancelado</span>';
                                break;
                        }

                        let pagamento = locacao.pagamento == 1 ?
                            `<a href="${basePagamentoUrl}${locacao.id}"><span class="btn btn-success">Pago</span></a>` :
                            `<a href="${basePagamentoUrl}${locacao.id}"><span class="btn btn-warning">Pendente</span></a>`;

                        return `
                    <tr>
                        <td>${locacao.id}</td>
                        <td>${locacao.created_at}</td>
                        <td><a href="locacoes/resumo/${locacao.id}">${locacao.cliente_nome || locacao.cliente_razao_social}</a></td>
                        <td>${locacao.data_entrega}<br>${locacao.data_devolucao}</td>
                        <td>${parseFloat(locacao.valor_total).toLocaleString('pt-BR', {style:'currency', currency:'BRL'})}</td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Mais</button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" target="_blank" href="<?= base_url('locacoes/contrato/') ?>${locacao.id}">Emitir Contrato</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('locacoes/edita/') ?>${locacao.id}">Editar Contrato</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('locacoes/cancelar/') ?>${locacao.id}">Cancelar Contrato</a></li>
                                </ul>
                            </div>
                        </td>
                        <td>${badgeHtml}</td>
                        <td>${pagamento}</td>
                        <td>${locacao.forma_pagamento || ''}</td>
                    </tr>
                `;
                    }).join('');

                    tabelaBody.innerHTML = rows;
                })
                .catch(error => {
                    tabelaBody.innerHTML = `<tr><td colspan='9' class='text-center text-danger'>Erro: ${error.message}</td></tr>`;
                });
        }


        // Eventos
        if (buscarBtn) {
            buscarBtn.addEventListener("click", function() {
                buscarLocacoes();
            });
        }

        if (palavraInput) {
            palavraInput.addEventListener("keypress", function(e) {
                if (e.key === "Enter") {
                    e.preventDefault();
                    buscarLocacoes();
                }
            });
        }
    });
</script>
<?= $this->endSection(); ?>