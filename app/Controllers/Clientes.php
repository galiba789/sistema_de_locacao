<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ConsultasCep;
use CodeIgniter\HTTP\ResponseInterface;

class Clientes extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        
        return view('dashboard/cadastros/clientes/index');
    }

    public function cadastrar(){
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        
        return view('dashboard/cadastros/clientes/cadastro');
    }

    public function salvar(){
        
    }

    public function consulta()
	{
        $cep = $this->request->getPost('cep');
        
        $Consulta = new ConsultasCep();
        
        echo $Consulta->consulta($cep);
    }
}
