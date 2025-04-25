<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LocacoesModel;
use CodeIgniter\HTTP\ResponseInterface;

class Faturamentos extends BaseController
{
    public function index()
    {
        $ano = $this->request->getGet('ano') ?? date('Y');
        $mes = $this->request->getGet('mes') ?? date('m');
        $status = $this->request->getGet('status'); // '0', '1' ou null

        $model = new LocacoesModel();

        $resumo = $model->getResumoMensal($ano, $mes, $status);
        $produtos = $model->getProdutosMaisAlugados($ano, $mes, $status);
        $categoria = $model->getCategoriaMaisUsada($ano, $mes, $status);

        return view('dashboard/faturamento/index', [
            'resumo'    => $resumo,
            'produtos'  => $produtos,
            'categoria' => $categoria,
            'ano'       => $ano,
            'mes'       => $mes,
            'status'    => $status
        ]);
    }


    public function produtos()
    {
        $dataInicio = $this->request->getGet('data_inicio') ?? date('Y-m-01');
        $dataFim    = $this->request->getGet('data_fim')    ?? date('Y-m-d');

        $locacaoModel = new LocacoesModel();

        $produtos = $locacaoModel->getProdutosRelatorio($dataInicio, $dataFim, );

        return view('dashboard/faturamento/produtos', [
            'produtos'   => $produtos,
            'filtros'    => compact('dataInicio','dataFim'),
        ]);
    }

    public function categorias()
    {
        $dataInicio  = $this->request->getGet('data_inicio') ?? date('Y-m-01');
        $dataFim     = $this->request->getGet('data_fim')    ?? date('Y-m-d');

        $locacaoModel = new LocacoesModel();
        $categorias = $locacaoModel->getCategoriasRelatorio($dataInicio, $dataFim, );

        return view('dashboard/faturamento/categorias', [
            'categorias' => $categorias,
            'filtros'    => compact('dataInicio','dataFim'),
        ]);
    }


    public function locacoes()
    {
        $dataInicio = $this->request->getGet('data_inicio');
        $dataFim = $this->request->getGet('data_fim');
        $status = $this->request->getGet('status');

        $locacoes = [];
        $valorTotal = 0;

        $locacaoModel = new LocacoesModel();

        if ($dataInicio && $dataFim) {
            $locacoes = $locacaoModel->getLocacoesPorPeriodo($dataInicio, $dataFim, $status);

            foreach ($locacoes as $locacao) {
                $valorTotal += $locacao->valor_total;
            }
        }

        return view('dashboard/faturamento/locacoes', [
            'locacoes' => $locacoes,
            'valorTotal' => $valorTotal,
            'filtros' => [
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim,
                'status' => $status,
            ]
        ]);
    }
}
