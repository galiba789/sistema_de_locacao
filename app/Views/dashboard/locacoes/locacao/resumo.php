<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>
<div class="content-wrapper">
<div class="container mt-4">
    <h2>Resumo da Locação #<?= esc($locacao['id']) ?></h2>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Detalhes da Locação
        </div>
        <div class="card-body">
            <p><strong>Cliente:</strong> <?= esc($locacao['cliente_nome']) ?></p>
            <p><strong>Data de Início:</strong> <?= date('d/m/Y', strtotime($locacao['data_entrega'])) ?></p>
            <p><strong>Data de Fim:</strong> <?= date('d/m/Y', strtotime($locacao['data_devolucao'])) ?></p>
            <p><strong>Status: </strong> <?php if ($locacao['situacao'] == 1): ?>
                                   Agendado
                                <?php elseif ($locacao['situacao'] == 2): ?>
                                    Pendente
                                <?php elseif ($locacao['situacao'] == 3): ?>
                                    Atrasado
                                <?php elseif ($locacao['situacao'] == 4): ?>
                                   Finalizada
                                <?php elseif ($locacao['situacao'] == 5): ?>
                                    Cancelado
                                <?php endif; ?></p>
                               
            <p><strong>Total:</strong> R$ <?= number_format($locacao['valor_total'], 2, ',', '.') ?></p>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-secondary text-white">
            Produtos Alugados
        </div>
        <div class="card-body">
            <?php if (empty($locacao['produtos'])): ?>
                <p>Nenhum produto encontrado para esta locação.</p>
            <?php else: ?>
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário (R$)</th>
                            <th>Subtotal (R$)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($locacao['produtos'] as $produto): ?>
                            <tr>
                                <td><?= esc($produto['produto_nome']) ?></td>
                                <td><?= esc($produto['quantidade']) ?></td>
                                <td><?= number_format($produto['preco_produto_original'], 2, ',', '.') ?></td>
                                <td>
                                    R$ <?= number_format($produto['quantidade'] * $produto['preco_produto_original'], 2, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="<?= base_url('locacoes') ?>" class="btn btn-outline-secondary">Voltar</a>
    </div>
</div>
<?= $this->endSection(); ?> 