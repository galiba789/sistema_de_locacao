<?php

namespace App\Controllers;

use App\Models\Clientes;
use App\Models\LocacoesModel;
use App\Models\LocacoesProdutosModel;

class Home extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $locacoesModel = new LocacoesModel();
        $clientesModel = new Clientes();

        $dias = isset($_GET['dias']) ? (int) $_GET['dias'] : 30; // Default 30 dias

        $locacoes = $locacoesModel
            ->select('locacao.*, C.nome, C.razao_social, C.tipo') 
            ->join('clientes C', 'locacao.cliente_id = C.id')
            ->where('locacao.situacao !=', 5)
            ->where('locacao.created_at >=', date('Y-m-d H:i:s', strtotime("-$dias days")))
            ->orderBy('locacao.created_at', 'DESC')
            ->findAll();
    

        // Obtendo o faturamento mensal de locações finalizadas (situação = 4)
        $faturamento = $locacoesModel
            ->select("DATE_FORMAT(created_at, '%M') as mes, SUM(valor_total) as total")
            ->where('situacao', 4)
            ->groupBy('mes')
            ->orderBy('MIN(created_at)', 'ASC')
            ->findAll();

        // Preparando os dados para o gráfico
        $meses = [];
        $valores = [];
        foreach ($faturamento as $linha) {
            $meses[] = $linha['mes'];
            $valores[] = $linha['total'];
        }

        $dados = [
            'locacoes' => $locacoes,
            'meses' => json_encode($meses), 
            'valores' => json_encode($valores),
        ];

        return view('dashboard/home/index', $dados);
    }
}
