<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>
<div class="content-wrapper">
    <div class="container mt-4">
        <!-- Página de Anexar e Visualizar Comprovante -->
        <div class="container mt-4">
            <h3>Comprovante de Pagamento da Locação</h3>
            <p>Locação ID: <?= $locacao['id'] ?></p>

            <!-- Ver Comprovantes -->
            <div class="mb-4">
                <h5>Comprovantes Enviados:</h5>


                <?php if (!empty($anexos) && count($anexos) > 0): ?>
                    <?php foreach ($anexos as $item): ?>
                        <a href="<?= base_url('public/uploads/comprovantes/' . $item['anexo']) ?>"
                            target="_blank"
                            class="btn btn-primary btn-sm mb-1">
                            Ver Comprovante #<?= $item['id'] ?>
                        </a>
                        <br>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum comprovante enviado.</p>
                <?php endif; ?>
            </div>
            <hr>

            <!-- Upload Novo Comprovante -->
            <h5>Enviar / Atualizar Comprovante</h5>
            <form action="<?= base_url('locacoes/anexos/salvar/' . $locacao['id']) ?>"
                method="post" enctype="multipart/form-data">


                <div class="form-group mb-3">
                    <label for="comprovante">Selecione o arquivo (PDF, JPG, PNG):</label>
                    <input type="file" name="comprovante" id="comprovante" class="form-control" required>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="submit" class="btn btn-success">Enviar Comprovante</button>
                    <a href="<?= base_url('locacoes') ?>" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>