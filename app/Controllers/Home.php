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
            ->orderBy('locacao.id', 'DESC') // ALTERADO PARA ORDENAR PELO ID CRESCENTE
            ->findAll();


            $faturamento = $locacoesModel
            ->select("DATE_FORMAT(created_at, '%m') as mes, 
                      DATE_FORMAT(created_at, '%M') as nome_mes, 
                      SUM(valor_total) as total")
            ->where('situacao !=', 5)
            ->groupBy('mes') 
            ->groupBy('nome_mes') 
            ->orderBy('mes', 'ASC')
            ->findAll();        
        
        // Preparando os dados para o gráfico
        $meses = [];
        $valores = [];
        foreach ($faturamento as $linha) {
            $meses[] = $linha['nome_mes']; // Nome do mês por extenso
            $valores[] = (float) $linha['total']; // Garantindo que os valores sejam float
        }

        $dados = [
            'locacoes' => $locacoes,
            'meses' => json_encode($meses),
            'valores' => json_encode($valores),
        ];

        return view('dashboard/home/index', $dados);
    }
}
