<?php

namespace App\Controllers;

use App\Models\Clientes;
use App\Models\LocacoesModel;

class Home extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $locacoesModel = new LocacoesModel();

        // Últimos X dias (padrão 30)
        $dias = isset($_GET['dias']) ? (int) $_GET['dias'] : 30;
        $dataInicio = date('Y-m-d', strtotime("-$dias days"));
        $dataFim = date('Y-m-d');

        // Pega locações recentes usando a função adaptada
        $locacoes = $locacoesModel->getLocacoesPorPeriodo($dataInicio, $dataFim);

        // Total do período
        $totalPeriodo = 0;
        foreach ($locacoes as $l) {
            $totalPeriodo += $l->valor_total; // <-- ajuste para o campo correto do valor
        }

        // Monta intervalo de meses para gráfico (últimos 6 meses)
        $grafInicio = date('Y-m-01', strtotime("-5 months"));
        $grafFim    = date('Y-m-t');

        // Pega faturamento mensal agregado pelo DB
        $faturamentoDb = $locacoesModel->getFaturamentoMensal($grafInicio, $grafFim);

        // Cria mapa ano_mes => total
        $map = [];
        foreach ($faturamentoDb as $row) {
            $map[$row->ano_mes] = (float) $row->total;
        }

        // Garante os últimos 6 meses na ordem correta (meses com 0 aparecem)
        setlocale(LC_TIME, 'pt_BR.UTF-8');
        $meses = [];
        $valores = [];
        for ($i = 5; $i >= 0; $i--) {
            $ym = date('Y-m', strtotime("-{$i} months"));
            $mesNome = ucfirst(strftime('%B', strtotime($ym . '-01')));
            $meses[] = $mesNome;

            // Se for o mês atual, usa total do período
            if ($i === 0) {
                $valores[] = round($totalPeriodo, 2); // bate com o total exibido
            } else {
                $valores[] = isset($map[$ym]) ? round((float)$map[$ym], 2) : 0.00;
            }
        }
        print_r($valores);
        exit;
        $dados = [
            'locacoes'       => $locacoes,
            'meses'          => json_encode($meses, JSON_UNESCAPED_UNICODE),
            'valores'        => json_encode($valores),
            'total_periodo'  => number_format($totalPeriodo, 2, ',', '.'),
        ];

        return view('dashboard/home/index', $dados);
    }
}
