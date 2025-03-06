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
        ->select('locacao.*, C.nome, C.razao_social, C.tipo') 
        ->join('clientes C', 'locacao.cliente_id = C.id')
        ->where('MONTH(locacao.created_at)', date('m'))  
        ->where('YEAR(locacao.created_at)', date('Y'))  
        ->orderBy('locacao.created_at', 'DESC')  
        ->findAll();  
        
        // print_r($locacoes);
        // exit;

        $dados = [
            'locacoes' => $locacoes,
        ];

        return view('dashboard/home/index', $dados);
    }


}
