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

        <h1>Orçamento</h1>
        <div class="card p-3">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label for="tipo" class="form-label search">Tipo</label>
                    <select id="tipo" class="form-control">
                        <option value="">Selecione</option>
                        <option value="1">Data</option>
                        <option value="2">Nome</option>
                        <option value="3">Razão Social</option>
                        <option value="4">Código</option>
                    </select>
                </div>
                <div class="col-md-3">
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
                    <a href="<?= base_url('orcamento/cadastrar') ?>">
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
                        <th>Forma de Pagamento</th>
                    </tr>
                </thead>
                <tbody id="tabela-locacoes">
                    <?php foreach ($orcamentos as $orcamento): ?>
                    <tr>
                        <td><?= $orcamento['id'] ?></td>
                        <td><?= $orcamento['created_at'] ?></td>
                        <td><a href="clientes/historico/<?=$orcamento['cliente_id']?>"><?= $orcamento['cliente_nome'] ?></a></td>
                        <td><?= $orcamento['data_entrega'] ?> <br><?= $orcamento['data_devolucao'] ?></td>
                        <td>R$ <?= $orcamento['valor_total'] ?></td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Mais
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" target="_blank" href="<?= base_url('orcamento/fazerContrato/') . $orcamento['id'] ?>">Fazer Contrato</a></li>
                                    <li><a class="dropdown-item" target="_blank" href="<?= base_url('orcamento/contrato/') . $orcamento['id'] ?>">Ver Orçamento</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('orcamento/edita/') . $orcamento['id'] ?>">Editar Orçamento</a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('orcamento/cancelar/') . $orcamento['id'] ?>">Cancelar Orçamento</a></li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            <?php if ($orcamento['situacao'] == 1): ?>
                                <span class="btn btn-info">Agendado</span>
                            <?php elseif ($orcamento['situacao'] == 2): ?>
                                <span class="btn btn-warning">Pendente</span>
                            <?php elseif ($orcamento['situacao'] == 3): ?>
                                <span class="btn btn-danger">Atrasado</span>
                            <?php elseif ($orcamento['situacao'] == 4): ?>
                                <span class="btn btn-success">Finalizada</span>
                            <?php elseif ($orcamento['situacao'] == 5): ?>
                                <span class="btn btn-danger">Cancelado</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $orcamento['forma_pagamento'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                <?php echo $paginacao->links('default', 'custom_pager')?>
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
        const baseUrl = "<?= base_url('orcamento/buscar') ?>";
        let queryParams = [];

        if (tipoSelect.value) {
            queryParams.push(`tipo=${encodeURIComponent(tipoSelect.value)}`);
        }

        if (palavraInput.value.trim()) {
            queryParams.push(`palavra=${encodeURIComponent(palavraInput.value.trim())}`);
        }

        if (situacaoSelect.value) {
            queryParams.push(`situacao=${encodeURIComponent(situacaoSelect.value)}`);
        }

        const url = queryParams.length > 0 ? `${baseUrl}?${queryParams.join('&')}` : baseUrl;

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Dados recebidos:", data); 
                tabelaBody.innerHTML = "";

                if (!data || data.length === 0) {
                    tabelaBody.innerHTML = "<tr><td colspan='9' class='text-center'>Nenhuma orçameto encontrado</td></tr>";
                    return;
                }

                let rows = data.map(orcamento => {
                    console.log(orcamento);
                    let badgeHtml = "";
                    switch (parseInt(orcamento.situacao)) {
                        case 1:
                            badgeHtml = '<span class="btn btn-info">Agendado</span>';
                            break;
                        case 2:
                            badgeHtml = '<span class="btn btn-warning">Pendente</span>';
                            break;
                        case 3:
                            badgeHtml = '<span class="btn btn-danger">Atrasado</span>';
                            break;
                        case 4:
                            badgeHtml = '<span class="btn btn-success">Finalizada</span>';
                            break;
                        case 5:
                            badgeHtml = '<span class="btn btn-danger">Cancelado</span>';
                            break;
                        default:
                            badgeHtml = '<span class="btn btn-secondary">Desconhecido</span>';
                    }


                    let dataEntrega = orcamento.data_entrega ? new Date(orcamento.data_entrega).toLocaleDateString('pt-BR') : '';
                    let dataDevolucao = orcamento.data_devolucao ? new Date(orcamento.data_devolucao).toLocaleDateString('pt-BR') : '';
                    let valorTotal = orcamento.valor_total ? parseFloat(orcamento.valor_total).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' }) : 'R$ 0,00';

                    return `
                        <tr>
                            <td>${orcamento.id || ''}</td>
                            <td>${orcamento.created_at || ''}</td>
                             <td><a href="clientes/historico/${orcamento.cliente_id}">${orcamento.cliente_nome || orcamento.cliente_razao_social}</a></td>
                            <td>${orcamento.data_entrega }<br>${orcamento.data_devolucao}</td>
                            <td>${valorTotal}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                        Mais
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" target="_blank" href="<?= base_url('orcamento/contrato/') ?>${orcamento.id}">Emitir Orçamento</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('orcamento/edita/') ?>${orcamento.id}">Editar Orçamento</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('orcamento/cancelar/') ?>${orcamento.id}">Cancelar Orçamento</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td>${badgeHtml}</td>
                            <td>${orcamento.forma_pagamento || ''}</td>
                        </tr>
                    `;
                }).join('');

                tabelaBody.innerHTML = rows;

                // Reativar os dropdowns após atualizar o conteúdo
                var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
                dropdownElementList.map(function (dropdownToggleEl) {
                    return new bootstrap.Dropdown(dropdownToggleEl);
                });
            })
            .catch(error => {
                console.error("Erro na busca:", error);
                tabelaBody.innerHTML = `<tr><td colspan='9' class='text-center text-danger'>Erro ao buscar dados: ${error.message}</td></tr>`;
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