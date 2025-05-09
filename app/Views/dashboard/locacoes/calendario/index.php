<?= $this->extend('dashboard/layout'); ?>
<?= $this->section('content-wrapper'); ?>

<?php setlocale(LC_TIME, 'pt_BR.UTF-8'); ?>

<style>
    .calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }
    .day {
        border: 1px solid #ddd;
        padding: 5px;
        min-height: 100px;
        background-color: #f8f9fa;
        position: relative;
    }
    .day .date {
        font-weight: bold;
    }
    .locacoes {
        margin-top: 5px;
    }
    .locacao {
        color: white;
        padding: 5px;
        border-radius: 3px;
        margin-bottom: 2px;
        font-size: 12px;
    }
    .navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .link{
        color: #ffffff !important;
    }
</style>

<div class="content-wrapper">
    <div class="container mt-4">
        <h2 class="text-center">Calendário de Locações</h2>
    
        <div class="navigation mb-3">
            <a  href="<?= base_url("/calendario/index/" . ($mes - 1 < 1 ? 12 : $mes - 1) . '/' . ($mes - 1 < 1 ? $ano -1 : $ano)) ?>" class="btn btn-secondary link"> Mês Anterior</a>
            <h4><?= ucfirst(strftime('%B de %Y', mktime(0, 0, 0, $mes, 1, $ano))) ?></h4>
            <a href="<?= base_url("/calendario/index/" . ($mes + 1 > 12 ? 1 : $mes + 1) . '/' . ($mes + 1 > 12 ? $ano +1 : $ano)) ?>" class="btn btn-secondary link">Próximo Mês</a>
        </div>
    
        <div class="calendar">
            <?php
                $diasSemana = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
                foreach ($diasSemana as $diaSemana) {
                    echo "<div class='text-center font-weight-bold'>$diaSemana</div>";
                }

                $primeiroDiaDoMes = date('w', strtotime("$ano-$mes-01"));
                $totalDiasMes = date('t', strtotime("$ano-$mes-01"));

                // Preencher os dias vazios no início do mês
                for ($i = 0; $i < $primeiroDiaDoMes; $i++) {
                    echo "<div class='day'></div>";
                }

                // Mostrar os dias do mês
                for ($dia = 1; $dia <= $totalDiasMes; $dia++) {
                    echo "<div class='day'>";
                    echo "<div class='date'>$dia</div>";

                    if (isset($locacoesPorDia[$dia])) {
                        echo "<div class='locacoes'>";
                        foreach ($locacoesPorDia[$dia] as $locacao) {
                            // Exibe apenas se o dia for o mesmo da retirada
                            $dataEntrega = strtotime($locacao['data_entrega']);
                            if (date('j', $dataEntrega) == $dia) {
                                // Cor de fundo conforme situação
                                $classe = match ($locacao['situacao']) {
                                    1 => 'bg-info',
                                    4 => 'bg-danger',
                                    5 => 'bg-warning',
                                    default => 'bg-info',
                                };

                                echo "<div class='locacao $classe'>";
                                echo "<a class='link' href='" . base_url("clientes/historico/{$locacao['cliente_id']}") . "' target='_blank'>{$locacao['cliente_nome']}</a>";
                                echo "</div>";
                            }
                        }
                        echo "</div>";
                    }

                    echo "</div>";
                }
            ?>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
