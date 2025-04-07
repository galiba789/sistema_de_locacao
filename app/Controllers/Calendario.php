<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Clientes;
use App\Models\LocacoesModel;
use CodeIgniter\HTTP\ResponseInterface;

class Calendario extends BaseController
{
    public function index($mes = null, $ano = null)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $mes = $mes ?? date('n');
        $ano = $ano ?? date('Y');

        // Ajuste para meses inválidos
        if ($mes < 1) {
            $mes = 12;
            $ano--;
        } elseif ($mes > 12) {
            $mes = 1;
            $ano++;
        }

        $locacoesModel = new LocacoesModel();
        $locacoes = $locacoesModel->getAtivosPorMes($mes, $ano);

        // Organizar as locações por dia
        $locacoesPorDia = [];
        
        foreach ($locacoes as $locacao) {
            $clientesModel = new Clientes();
            foreach ($locacoes as &$locacao) {
                $cliente = $clientesModel->find($locacao['cliente_id']);
    
                if ($cliente) {
                    if ($cliente['tipo'] == 1) {
                        $locacao['cliente_nome'] = $cliente['nome'];
                    } else if ($cliente['tipo'] == 2) {
                        $locacao['cliente_nome'] = $cliente['razao_social'];
                    } else {
                        $locacao['cliente_nome'] = 'Tipo de cliente desconhecido';
                    }
                } else {
                    $locacao['cliente_nome'] = 'Desconhecido';
                }
            }
            $dataInicio = strtotime($locacao['data_entrega']);
            $dataFim = strtotime($locacao['data_devolucao']);
            
            // Garantir que consideramos apenas datas dentro do mês atual
            $inicio = max($dataInicio, strtotime("$ano-$mes-01"));
            $fim = min($dataFim, strtotime("$ano-$mes-" . date('t', strtotime("$ano-$mes-01"))));
            
            // Iterar por cada dia no intervalo
            for ($data = $inicio; $data <= $fim; $data = strtotime('+1 day', $data)) {
                $dia = date('j', $data);
                $locacoesPorDia[$dia][] = $locacao;
            }
        }

        $data = [
            'mes' => $mes,
            'ano' => $ano,
            'locacoesPorDia' => $locacoesPorDia,
        ];

        return view('dashboard/locacoes/calendario/index', $data);
    }
}