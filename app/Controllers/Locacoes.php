<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Clientes;
use App\Models\ProdutosModel;
use CodeIgniter\HTTP\ResponseInterface;

class Locacoes extends BaseController
{
    public function index()
    {
        
        return view('dashboard/locacoes/locacao/index');
    }

    public function cadastrar(){
        $clienteModels = new Clientes();
        $produtosModels = new ProdutosModel();

        $data = [
            'clientes' => $clienteModels->getAtivos(),
        ];

        return view('dashboard/locacoes/locacao/cadastrar', $data);
    }
}
