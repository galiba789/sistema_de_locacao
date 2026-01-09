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
                    <?php foreach ($anexos as $anexo): ?>
                        <div class="mb-2">
                            <a href="<?= base_url('public/uploads/comprovantes/' . $anexo['anexo']) ?>" target="_blank">
                                Ver comprovante
                            </a>

                            <button type="button"
                                class="btn btn-sm btn-danger"
                                onclick="removerAnexo(<?= $anexo['id'] ?>)">
                                Excluir
                            </button>
                        </div>
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
                    <a href="<?= base_url('locacoes?page=' . $page) ?>" class="btn btn-secondary">Voltar</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function removerAnexo(id) {
    if (!confirm('Deseja realmente excluir este comprovante?')) {
        return;
    }

    fetch('<?= base_url('locacoes/anexos/removerAnexo') ?>/' + id, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'ok') {
            location.reload();
        } else {
            alert('Erro ao excluir o anexo.');
        }
    });
}
</script>

<?= $this->endSection() ?>