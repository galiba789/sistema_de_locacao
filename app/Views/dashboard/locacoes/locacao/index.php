<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>
<?php 
function getStatusLabel($situacao)
{
    switch ($situacao) {
        case 1: return 'Agendado';
        case 2: return 'Pendente';
        case 3: return 'Atrasado';
        case 4: return 'Finalizada';
        case 5: return 'Cancelado';
        case 6: return 'Em andamento';
        default: return 'Desconhecido';
    }
}

function getStatusBtnClass($situacao)
{
    switch ($situacao) {
        case 1: return 'info';
        case 2: return 'warning';
        case 3: return 'danger';
        case 4: return 'success';
        case 5: return 'dark';
        case 6: return 'info';
        default: return 'secondary';
    }
}
?>

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
                
                <div class="col-md-5">
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
                            <td><a href="<?=base_url()?>locacoes/resumo/<?= $locacao['id'] ?>"><?= $locacao['cliente_nome'] ?></a></td>
                            <td>
                                <?= date("d/m/Y H:i:s", strtotime($locacao['data_entrega'])) ?> <br>
                                <?= date("d/m/Y H:i:s", strtotime($locacao['data_devolucao'])) ?>
                            </td>
                            <?php
                            $valor = number_format($locacao['valor_total'], 2, ',', '.');
                            ?>
                            <td>R$ <?= $valor ?></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Mais
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" target="_blank" href="<?= base_url('locacoes/contrato/') . $locacao['id'] ?>">Emitir Contrato</a></li>
                                        <li>                  <a class="dropdown-item" href="<?= base_url('locacoes/edita/') . $locacao['id'] . '?page=' . $pager ?>">
                                            Editar Contrato
                                        </a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('locacoes/cancelar/') . $locacao['id'] ?>" onclick="return confirm('Você tem certeza que deseja cancelar esse contrato ?')">Cancelar Contrato</a></li>
                                    </ul>
                                </div>
                            </td>
                          <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-<?= getStatusBtnClass($locacao['situacao']) ?> btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?= getStatusLabel($locacao['situacao']) ?>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php foreach ([1 => 'Agendado', 2 => 'Pendente', 3 => 'Atrasado', 6 => 'Em andamento', 4 => 'Finalizada', 5 => 'Cancelado'] as $valor => $label): ?>
                                            <li>
                                                <form action="<?= base_url('locacoes/alterar_situacao/' . $locacao['id']) ?>" method="POST">
                                                        <input type="hidden" name="situacao" value="<?= $valor ?>">
                                                        <input type="hidden" name="page" value="<?= esc($pager) ?>">
                                                        <button type="submit" class="dropdown-item <?= $locacao['situacao'] == $valor ? 'active' : '' ?>">
                                                            <?= $label ?>
                                                        </button>
                                                    </form>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <?php if ($locacao['pagamento'] == 0): ?>
                                    <a href="<?= base_url('locacoes/pagamento/') .  $locacao['id'] . '?page=' . $pager ?>"
                                        onclick="return confirm('Tem certeza que deseja marcar como PAGO?')">
                                        <span class="btn btn-warning">Pendente</span>
                                    </a>
                                <?php elseif ($locacao['pagamento'] == 1): ?>
                                    <a href="<?= base_url('locacoes/pagamento/') . $locacao['id'] . '?page=' . $pager ?>"
                                        onclick="return confirm('Tem certeza que deseja marcar como PENDENTE?')">
                                        <span class="btn btn-success">Pago</span>
                                    </a>
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
document.addEventListener('DOMContentLoaded', function () {
    const tabela = document.getElementById('tabela-locacoes');
    const buscaInput = document.getElementById('palavra');
    const situacaoSelect = document.getElementById('situacao');
    const buscarBtn = document.getElementById('buscar-btn');

    // Função para buscar via AJAX
    async function buscarLocacoes() {
        const palavra = buscaInput.value;
        const situacao = situacaoSelect.value;

        const url = `<?= base_url('locacoes/buscar') ?>?palavra=${encodeURIComponent(palavra)}&situacao=${encodeURIComponent(situacao)}`;

        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error('Erro na requisição');

            let locacoes = await response.json();
            locacoes = locacoes.slice(0, 10); // Limite de 10 itens

            tabela.innerHTML = '';

            locacoes.forEach(locacao => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${locacao.id}</td>
                    <td>${locacao.created_at}</td>
                    <td><a href="<?= base_url('locacoes/resumo/') ?>${locacao.id}">${locacao.cliente_nome || locacao.cliente_razao_social}</a></td>
                    <td>${locacao.data_entrega}<br>${locacao.data_devolucao}</td>
                    <td>R$ ${locacao.valor_total}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown">Mais</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" target="_blank" href="<?= base_url('locacoes/contrato/') ?>${locacao.id}">Emitir Contrato</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('locacoes/edita/') ?>${locacao.id}?page=<?= $pager ?>">Editar Contrato</a></li>
                                <li><a class="dropdown-item cancelar-btn" href="#" data-id="${locacao.id}">Cancelar Contrato</a></li>
                            </ul>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-${getStatusBtnClassJS(locacao.situacao)} btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                ${getStatusLabelJS(locacao.situacao)}
                            </button>
                            <ul class="dropdown-menu">
                                ${[1,2,3,6,4,5].map(valor => `
                                    <li>
                                        <a href="#" class="dropdown-item alterar-situacao-btn ${locacao.situacao == valor ? 'active' : ''}" data-id="${locacao.id}" data-situacao="${valor}">
                                            ${getStatusLabelJS(valor)}
                                        </a>
                                    </li>
                                `).join('')}
                            </ul>
                        </div>
                    </td>
                    <td>
    <button class="btn btn-sm pagamento-btn ${locacao.pagamento == 0 ? 'btn-warning' : 'btn-success'}" 
            data-id="${locacao.id}" 
            data-pagamento="${locacao.pagamento}">
        ${locacao.pagamento == 0 ? 'Pendente' : 'Pago'}
    </button>
</td>
                    <td>${locacao.forma_pagamento}</td>
                `;
                tabela.appendChild(row);
            });

            // Adiciona eventos aos botões gerados dinamicamente
            ativarBotoesSituacao();
            ativarBotoesPagamento();
            ativarBotaoCancelar();
        } catch (error) {
            console.error(error);
        }
    }

    // Funções auxiliares
    function getStatusLabelJS(situacao) {
        switch (parseInt(situacao)) {
            case 1: return 'Agendado';
            case 2: return 'Pendente';
            case 3: return 'Atrasado';
            case 4: return 'Finalizada';
            case 5: return 'Cancelado';
            case 6: return 'Em andamento';
            default: return 'Desconhecido';
        }
    }

    function getStatusBtnClassJS(situacao) {
        switch (parseInt(situacao)) {
            case 1: return 'info';
            case 2: return 'warning';
            case 3: return 'danger';
            case 4: return 'success';
            case 5: return 'dark';
            case 6: return 'info';
            default: return 'secondary';
        }
    }

    // Alterar situação via AJAX
    function ativarBotoesSituacao() {
        document.querySelectorAll('.alterar-situacao-btn').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const novaSituacao = this.dataset.situacao;

                try {
                    await fetch(`<?= base_url('locacoes/alterar_situacao/') ?>${id}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `situacao=${novaSituacao}`
                    });
                    buscarLocacoes(); // Atualiza a tabela
                } catch (err) {
                    console.error(err);
                }
            });
        });
    }

    // Alterar pagamento via AJAX
    function ativarBotoesPagamento() {
        document.querySelectorAll('.pagamento-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = this.dataset.id;
                const pagamentoAtual = this.dataset.pagamento;
                const novoPagamento = pagamentoAtual == 0 ? 1 : 0;

                try {
                    await fetch(`<?= base_url('locacoes/pagamento/') ?>${id}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: `pagamento=${novoPagamento}`
                    });
                    buscarLocacoes(); // Atualiza a tabela
                } catch (err) {
                    console.error(err);
                }
            });
        });
    }

    // Cancelar contrato
    function ativarBotaoCancelar() {
        document.querySelectorAll('.cancelar-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Tem certeza que deseja cancelar este contrato?')) {
                    const id = this.dataset.id;
                    window.location.href = `<?= base_url('locacoes/cancelar/') ?>${id}`;
                }
            });
        });
    }

    // Eventos principais
    buscarBtn.addEventListener('click', buscarLocacoes);
    situacaoSelect.addEventListener('change', buscarLocacoes);
    buscaInput.addEventListener('input', function() {
        if (buscaInput.value === '') buscarLocacoes();
    });

    // Buscar ao carregar se já tiver valor
    if (buscaInput.value !== '' || situacaoSelect.value !== '') {
        buscarLocacoes();
    }
    
    function ativarBotoesPagamento() {
    document.querySelectorAll('.pagamento-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const confirmMsg = this.dataset.pagamento == 0 
                ? 'Tem certeza que deseja marcar como PAGO?' 
                : 'Tem certeza que deseja marcar como PENDENTE?';

            if (!confirm(confirmMsg)) return;

            // Inclui page para o redirect funcionar
            const page = <?= $pager ?? 1 ?>;

            fetch(`<?= base_url('locacoes/pagamento/') ?>${id}?page=${page}`)
                .then(() => buscarLocacoes()) // atualiza a tabela
                .catch(err => console.error(err));
        });
    });
}

buscaInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { // detecta a tecla Enter
        e.preventDefault(); // previne o comportamento padrão do form
        buscarLocacoes();   // chama a função de busca
    }
});

});
</script>
<?= $this->endSection(); ?>