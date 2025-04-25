<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>

<div class="content-wrapper">
  <div class="container mt-4">

    <section class="content-header mb-4">
      <h1>Relatório de Produtos</h1>
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
          <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
      </form>

      <?php if (!empty($produtos)): ?>
        <div class="card">
          <div class="card-body table-responsive">
            <table id="tabela-produtos" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Produto</th>
                  <th>Qtd. Locações</th>
                  <th>Total Diárias</th>
                  <th>Preço Diária</th>
                  <th>Faturamento</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($produtos as $p): ?>
                  <tr>
                    <td><?= esc($p->produto) ?></td>
                    <td><?= esc($p->total_locacoes) ?></td>
                    <td><?= esc($p->total_diarias) ?></td>
                    <td>R$ <?= number_format($p->preco_diaria,2,',','.') ?></td>
                    <td>R$ <?= number_format($p->faturamento_real,2,',','.') ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php else: ?>
        <div class="alert alert-info">Nenhum produto encontrado no período.</div>
      <?php endif; ?>
    </section>
  </div>
</div>



<?= $this->endSection(); ?>
