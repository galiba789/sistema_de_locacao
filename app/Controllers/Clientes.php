<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Clientes as ModelsClientes;
use App\Models\ConsultasCep;
use App\Models\LocacoesModel;
use CodeIgniter\HTTP\ResponseInterface;

class Clientes extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        
        $clientesModel = new ModelsClientes();

         // Pega a página atual ou define 1 se não houver
         $pagina = $this->request->getVar('page') ?? 1;
    
         // Define o número de itens por página
         $itensPorPagina = 10;
     
         // Busca os dados paginados (mantém a variável locacoes paginada)
         $clientes = $clientesModel
            ->orderBy('clientes.created_at', 'DESC')
            ->paginate($itensPorPagina);
     
         // Gera os links de paginação automaticamente
         $paginacao = $clientesModel->pager;

        $dados = [
            'clientes' => $clientes,
            'paginacao' => $paginacao,
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
    
        if ($tipo == 1) { 
            $data = [
                'tipo' => $tipo,
                'nome' => $this->request->getPost('nome'),
                'cpf' => $this->request->getPost('cpf'),
                'rg' => $this->request->getPost('rg'),
                'email' => $this->request->getPost('email'),
                'telefone_contato' => $this->request->getPost('telefone_contato'),
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
        } elseif ($tipo == 2) { 
            $data = [
                'tipo' => $tipo,
                'razao_social' => $this->request->getPost('razao_social'),
                'cnpj' => $this->request->getPost('cnpj'),
                'telefone_comercial' => $this->request->getPost('telefone_comercial'),
                'email' => $this->request->getPost('email'),
                'obs' => $this->request->getPost('obs'),
                // Contato da empresa
                'email_contato' => $this->request->getPost('email_contato'),
                'telefone_contato' => $this->request->getPost('telefone_contato_cnpj'),
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
        } else {
            return redirect()->back()->withInput()->with('error', 'Tipo inválido de cliente.');
        }
    
        $id = $clientesModel->insert($data);
        if ($id) {
            return redirect()->to('/clientes')->with('success', 'Cliente cadastrado com sucesso!');
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
        $cliente = $clientesModel->find($id);
        
        if ($cliente['tipo'] == 1){
            $data = [
                'nome' => $this->request->getPost('nome'),
                'cpf' => $this->request->getPost('cpf'),
                'rg' => $this->request->getPost('rg'),
                'email' => $this->request->getPost('email'),
                'telefone_contato' => $this->request->getPost('telefone_contato'),
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

        } elseif($cliente['tipo'] == 2) {
            $data = [
                'razao_social' => $this->request->getPost('razao_social'),
                'cnpj' => $this->request->getPost('cnpj'),
                'telefone_comercial' => $this->request->getPost('telefone_comercial'),
                'email' => $this->request->getPost('email'),
                'obs' => $this->request->getPost('obs'),
                // Contato da empresa
                'email_contato' => $this->request->getPost('email_contato'),
                'telefone_contato' => $this->request->getPost('telefone_contato_cnpj'),
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

        $clientesModel->delete($id);
        return redirect()->to('/clientes')->with('success', 'Cliente desativado com sucesso.');
    }
    public function consulta()
	{
        $cep = $this->request->getPost('cep');
        
        $Consulta = new ConsultasCep();
        
        echo $Consulta->consulta($cep);
    }
    public function buscar()
    {
        $tipo = $this->request->getGet('tipo');
        $palavra = trim($this->request->getGet('palavra'));
    
        $clientesModel = new ModelsClientes();
    
        // Se a palavra estiver vazia, retorna apenas os primeiros 50 registros para evitar sobrecarga
        if (empty($palavra)) {
            $clientes = $clientesModel->orderBy('id', 'DESC')->limit(50)->findAll();
            return $this->response->setJSON($clientes);
        }
    
        // Filtra pelos campos permitidos
        $camposPermitidos = ['nome', 'cpf', 'cnpj', 'id'];
        if (!in_array($tipo, $camposPermitidos)) {
            return $this->response->setJSON([]); // Retorna vazio se o tipo for inválido
        }
    
        // Busca com filtro
        $clientes = $clientesModel
            ->select('id, nome, razao_social, cpf, cnpj, email, telefone_contato, tipo')
            ->like($tipo, $palavra)
            ->orderBy('id', 'DESC')
            ->findAll();
    
        return $this->response->setJSON($clientes);
    }
    
    public function historico($id){
        $clientesModel = new ModelsClientes();
        $locacaoModel = new LocacoesModel();

        $cliente = $clientesModel->find($id);
        $locacao = $locacaoModel->where('cliente_id =', $id)
                ->find();
        
        $dados = [
            'cliente' => $cliente,
            'locacoes' => $locacao,
        ];

        return view('dashboard/cadastros/clientes/cliente', $dados);
    }

}
