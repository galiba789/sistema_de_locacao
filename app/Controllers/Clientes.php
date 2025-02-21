<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Clientes as ModelsClientes;
use App\Models\ConsultasCep;
use CodeIgniter\HTTP\ResponseInterface;

class Clientes extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        
        $clientesModel = new ModelsClientes();
        
        // print_r($clientesModel->getAtivos());
        // exit;

        $dados = [
            'clientes' => $clientesModel->getAtivos(),
        ];

        return view('dashboard/cadastros/clientes/index', $dados);
    }

    public function cadastrar(){
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        
        return view('dashboard/cadastros/clientes/cadastro');
    }

    public function salvar(){
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $clientesModel = new ModelsClientes();

        $tipo = $this->request->getPost('type');
        // print_r($tipo);
        // exit;


        if ($tipo = 1){
            $data = [
                'tipo' => $tipo,
                'nome' => $this->request->getPost('nome'),
                'cpf' => $this->request->getPost('cpf'),
                'rg' => $this->request->getPost('rg'),
                'email' => $this->request->getPost('email'),
                'telefone' => $this->request->getPost('telefone'),
                'nascimento' => $this->request->getPost('nascimento'),
                'obs' => $this->request->getPost('obs'),
                // endereço
                'cep' => $this->request->getPost('cep'),
                'logradouro' => $this->request->getPost('logradouro'),
                'numero' => $this->request->getPost('numero'),
                'complemento' => $this->request->getPost('complemento'),
                'bairro' => $this->request->getPost('bairro'),
                'estado' => $this->request->getPost('estado'),
                'localidade' => $this->request->getPost('localidade'),
            ];
            $id = $clientesModel->insert($data);
            if (is_int($id)) {
                return redirect()->to('/clientes')->with('success', 'Cliente cadastrada com sucesso!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
            }

        } elseif($tipo = 2) {
            $data = [
                'tipo' => $this->request->getPost('tipo'),
                'razao_social' => $this->request->getPost('razao_social'),
                'cnpj' => $this->request->getPost('cnpj'),
                'telefone_comercial' => $this->request->getPost('telefone_comercial'),
                'email' => $this->request->getPost('email'),
                'obs' => $this->request->getPost('obs'),
                // Contato da empresa
                'email_contato' => $this->request->getPost('email_contato'),
                'telefone_contato' => $this->request->getPost('telefone_contato'),
                'cargo' => $this->request->getPost('cargo'),
                // endereço
                'cep' => $this->request->getPost('cep'),
                'logradouro' => $this->request->getPost('logradouro'),
                'numero' => $this->request->getPost('numero'),
                'complemento' => $this->request->getPost('complemento'),
                'bairro' => $this->request->getPost('bairro'),
                'estado' => $this->request->getPost('estado'),
                'localidade' => $this->request->getPost('localidade'),
            ];
            $id = $clientesModel->insert($data);
            if (is_int($id)) {
                return redirect()->to('/clientes')->with('success', 'Cliente cadastrada com sucesso!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
            }
        } else {
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
        }


    }

    public function editar($id){
        $clientesModel = new ModelsClientes();
        $dados = [
            'cliente' => $clientesModel->find($id),
        ];

        return view('dashboard/cadastros/clientes/editar',$dados);
    }

    public function update($id){
        $clientesModel = new ModelsClientes();
        $tipo = $clientesModel->find($id);
        
        if ($tipo = 1){
            $data = [
                'tipo' => $tipo,
                'nome' => $this->request->getPost('nome'),
                'cpf' => $this->request->getPost('cpf'),
                'rg' => $this->request->getPost('rg'),
                'email' => $this->request->getPost('email'),
                'telefone' => $this->request->getPost('telefone'),
                'nascimento' => $this->request->getPost('nascimento'),
                'obs' => $this->request->getPost('obs'),
                // endereço
                'cep' => $this->request->getPost('cep'),
                'logradouro' => $this->request->getPost('logradouro'),
                'numero' => $this->request->getPost('numero'),
                'complemento' => $this->request->getPost('complemento'),
                'bairro' => $this->request->getPost('bairro'),
                'estado' => $this->request->getPost('estado'),
                'localidade' => $this->request->getPost('localidade'),
            ];
            $id = $clientesModel->update($id, $data);
            if ($id) {
                return redirect()->to('/clientes')->with('success', 'Cliente cadastrada com sucesso!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
            }

        } elseif($tipo = 2) {
            $data = [
                'tipo' => $this->request->getPost('tipo'),
                'razao_social' => $this->request->getPost('razao_social'),
                'cnpj' => $this->request->getPost('cnpj'),
                'telefone_comercial' => $this->request->getPost('telefone_comercial'),
                'email' => $this->request->getPost('email'),
                'obs' => $this->request->getPost('obs'),
                // Contato da empresa
                'email_contato' => $this->request->getPost('email_contato'),
                'telefone_contato' => $this->request->getPost('telefone_contato'),
                'cargo' => $this->request->getPost('cargo'),
                // endereço
                'cep' => $this->request->getPost('cep'),
                'logradouro' => $this->request->getPost('logradouro'),
                'numero' => $this->request->getPost('numero'),
                'complemento' => $this->request->getPost('complemento'),
                'bairro' => $this->request->getPost('bairro'),
                'estado' => $this->request->getPost('estado'),
                'localidade' => $this->request->getPost('localidade'),
            ];
            $id = $clientesModel->update($id, $data);
            if ($id) {
                return redirect()->to('/clientes')->with('success', 'Cliente cadastrada com sucesso!');
            } else {
                return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
            }
        } else {
          
        }
    }


    public function excluir($id){
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $clientesModel = new ModelsClientes();
        $clientesModel->find($id);

        $dados = [
            'status' => 0,
        ];

        $clientesModel->update($id, $dados);
        return redirect()->to('/clientes')->with('success', 'Cliente desativado com sucesso.');
    }
    public function consulta()
	{
        $cep = $this->request->getPost('cep');
        
        $Consulta = new ConsultasCep();
        
        echo $Consulta->consulta($cep);
    }
}
