<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>

<div class="content-wrapper">
    <div class="container mt-4">
        <section class="content-header mb-3">
            <h1>Relatório de Categorias</h1>
        </section>
        <section class="content">
            <form class="row g-3 mb-4">
                <div class="col-sm-3">
                    <label>Data Início</label>
                    <input type="date" name="data_inicio" class="form-control"
                        value="<?= esc($filtros['dataInicio']) ?>">
                </div>
                <div class="col-sm-3">
                    <label>Data Fim</label>
                    <input type="date" name="data_fim" class="form-control"
                        value="<?= esc($filtros['dataFim']) ?>">
                </div>
                <div class="col-sm-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-info w-100">Filtrar</button>
                </div>
            </form>

            <?php if (!empty($categorias)): ?>
                <div class="card">
                    <div class="card-body table-responsive p-0">
                        <table id="tabela-categorias" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Categoria</th>
                                    <th>Qtd. Locações</th>
                                    <th>Total Diárias</th>
                                    <th>Faturamento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categorias as $c): ?>
                                    <tr>
                                        <td><?= esc($c->categoria) ?></td>
                                        <td><?= esc($c->total_locacoes) ?></td>
                                        <td><?= esc($c->total_diarias) ?></td>
                                        <td>R$ <?= number_format($c->faturamento_total, 2, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Nenhuma categoria encontrada no período.</div>
            <?php endif; ?>
        </section>
    </div>
</div>



<?= $this->endSection(); ?>
