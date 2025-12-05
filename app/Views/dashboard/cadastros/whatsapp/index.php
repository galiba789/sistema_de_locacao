<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>

<div class="content-wrapper">
    <div class="container mt-4">
        <?php if (session()->getFlashdata('sucesso')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('sucesso') ?></div>
        <?php elseif (session()->getFlashdata('erro')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('erro') ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Instâncias</h3>

                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#selecionarInstanciaModal">
                        <i class="fa fa-brands fa-whatsapp"></i> Selecionar Instância
                    </button>

                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#novaInstancia">
                        <i class="fa fa-plus"></i> Nova Instância
                    </button>

                    <a href="whatsapp/teste">
                        <button class="btn btn-success">
                            <i class="fa fa-plus"></i> enviar mensagem
                        </button>
                    </a>

                </div>
            </div>

            <div class="card-body">

                <div id="instanciaSelecionada" class="mb-3 alert alert-info" style="display:none;">
                    <strong>Instância selecionada:</strong> <span id="instanciaNome"></span>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Número</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($instances as $i): ?>
                            <tr>
                                <td><?= esc($i['nome_instance']) ?></td>
                                <td><?= esc($i['numero']) ?></td>

                                <td>
                                    <?php if ($i['status'] === 'open'): ?>
                                        <span class="badge bg-success">Conectado</span>

                                    <?php elseif ($i['status'] === 'QRCODE'): ?>
                                        <span class="badge bg-warning">Aguardando QR</span>

                                    <?php else: ?>
                                        <span class="badge bg-secondary">Desconectado</span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($i['status'] === 'open'): ?>

                                        <a href="<?= site_url('whatsapp/desconectar/' . $i['nome_url']) ?>"
                                            class="btn btn-danger btn-sm">
                                            Desconectar
                                        </a>

                                    <?php else: ?>

                                        <a href="<?= site_url('whatsapp/conectar/' . $i['nome_url']) ?>"
                                            class="btn btn-primary btn-sm">
                                            Conectar
                                        </a>

                                        <?php if ($i['status'] === 'DISCONNECTED'): ?>
                                            <a href="<?= site_url('whatsapp/excluir/' . $i['id']) ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Tem certeza que deseja excluir esta instância?')">
                                                Excluir
                                            </a>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>

            </div>
        </div>

        <!-- Modal Nova Instância -->
        <div class="modal fade" id="novaInstancia">
            <div class="modal-dialog">
                <div class="modal-content">

                    <form method="POST" action="<?= site_url('/whatsapp/criar') ?>">
                        <?= csrf_field() ?>

                        <div class="modal-header">
                            <h4 class="modal-title">Nova Instância</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label>Nome da instância</label>
                                <input type="text" name="nome_instance" class="form-control" required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Número (com DDI)</label>
                                <input type="text" name="numero" class="form-control" placeholder="Ex: 5538999999999" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Criar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- Modal Selecionar Instância -->
        <div class="modal fade" id="selecionarInstanciaModal">
            <div class="modal-dialog">
                <div class="modal-content">

                    <form method="POST" action="<?= site_url('/whatsapp/set_instancia') ?>">
                        <?= csrf_field() ?>

                        <div class="modal-header">
                            <h4 class="modal-title">Selecionar Instância</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="form-group">
                                <label>Escolha a instância ativa</label>
                                <select name="instance" id="instance" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($instances as $i): ?>
                                        <option value="<?= esc($i['id']) ?>" data-nome="<?= esc($i['nome_instance']) ?>">
                                            <?= esc($i['nome_instance']) ?> - <?= esc($i['numero']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" id="confirmarInstancia" class="btn btn-primary">Selecionar</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('instance');
        const confirmarBtn = document.getElementById('confirmarInstancia');
        const displayDiv = document.getElementById('instanciaSelecionada');
        const displayNome = document.getElementById('instanciaNome');

        // Função para fechar modal (Bootstrap 5)
        function fecharModal() {
            const modalElement = document.getElementById('selecionarInstanciaModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) modalInstance.hide();
        }

        // Ao confirmar seleção
        confirmarBtn.addEventListener('click', function() {
            const id = select.value;
            const nome = select.options[select.selectedIndex]?.dataset.nome;

            if (!id) {
                alert('Selecione uma instância antes de confirmar.');
                return;
            }

            // Exibe a instância selecionada
            displayNome.textContent = nome;
            displayDiv.style.display = 'block';

            // Fecha modal
            fecharModal();

            // Salva no localStorage
            localStorage.setItem('instanciaSelecionada', JSON.stringify({
                id,
                nome
            }));

            // AGORA ENVIA O FORMULÁRIO
            document.querySelector('#selecionarInstanciaModal form').submit();
        });


        // Recupera instância salva ao carregar
        const data = localStorage.getItem('instanciaSelecionada');
        if (data) {
            const {
                id,
                nome
            } = JSON.parse(data);
            displayNome.textContent = nome;
            displayDiv.style.display = 'block';
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


<?= $this->endSection()  ?>