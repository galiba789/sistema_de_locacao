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
        $locacoesProdutos = new LocacoesProdutosModel();
        $clientesModel = new Clientes();

        $locacoes = $locacoesModel
        ->select('locacao.*, P.nome, P.numero_serie') 
        ->join('locacoes_produtos LP', 'locacao.id = LP.locacao_id') 
        ->join('produtos P', 'LP.produto_id = P.id')  
        ->where('MONTH(locacao.created_at)', date('m'))  
        ->where('YEAR(locacao.created_at)', date('Y'))  
        ->orderBy('locacao.created_at', 'DESC')  
        ->findAll();  
    

        $dados = [
            'locacoes' => $locacoes,
        ];

        return view('dashboard/home/index', $dados);
    }


}
