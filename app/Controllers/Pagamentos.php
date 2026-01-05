<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnexosModel;
use App\Models\Clientes;
use App\Models\LocacoesModel;
use App\Models\PagamentosModel;
use App\Models\WhatsappModel;
use CodeIgniter\HTTP\ResponseInterface;

class Pagamentos extends BaseController
{
    public function Anexos($id_locacao)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $page = $this->request->getVar('page');

        $locacoesModel = new LocacoesModel();
        $anexoModel = new AnexosModel();

        $locacao = $locacoesModel->find($id_locacao);
        $anexos = $anexoModel->where('locacao_id', $id_locacao)->findAll();

        $data = [
            'locacao' => $locacao,
            'anexos' => $anexos,
            'page' => $page
        ];

        return view('dashboard/locacoes/Anexos/index', $data);
    }

    public function salvar($id_locacao)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $anexosModel   = new AnexosModel();
        $locacoesModel = new LocacoesModel();
        $whatsappModel = new WhatsappModel();
        $ClientesModel = new Clientes();

        $zap = $whatsappModel->where('selecionada', 1)->findAll();

        // Verifica se a locação existe
        $locacao = $locacoesModel->find($id_locacao);

        if (!$locacao) {
            return redirect()->back()->with('error', 'Locação não encontrada.');
        }

        $cliente = $ClientesModel->find($locacao['cliente_id']);

        // Arquivo enviado
        $file = $this->request->getFile('comprovante');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Nenhum arquivo válido foi enviado.');
        }

        // Gera nome seguro
        $newName = $file->getRandomName();

        // Move o arquivo para a pasta correta
        $file->move('public/uploads/comprovantes/', $newName);

        // Salva no banco
        $anexosModel->insert([
            'locacao_id' => $id_locacao,
            'anexo'      => $newName,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $data = [
            'situacao' => 4,
            'pagamento' => 1,
        ];

        if($zap){
            if ($cliente['tipo'] == 1){
                $mensagem = "Olá ". $cliente['nome']." o pagamento de sua locação foi confirmada";
                $telefone = $cliente['telefone_contato'];
            } else {
                $mensagem = "Olá ". $cliente['cargo']." o pagamento de sua locação foi confirmada";   
                $telefone = $cliente['whatsapp'];
            }
            
            $whatsappModel->enviarMensagem($telefone, $mensagem);
            
        }
        $locacoesModel->update($id_locacao, $data);
        return redirect()->back()->with('success', 'Comprovante enviado com sucesso!');
    }

    public function index()
    {

        $pagamentosModel = new PagamentosModel();

        $pagamentos = $pagamentosModel->where('excluido', 0)->orderBy('id', 'DESC')
            ->paginate(10);

        $paginacao = $pagamentosModel->pager;

        $data = [
            'pagamentos' => $pagamentos,
            'paginacao' => $paginacao
        ];

        return view('dashboard/cadastros/pagamentos/index', $data);
    }

    public function cadastrar()
    {

        return view('dashboard/cadastros/pagamentos/cadastrar');
    }

    public function cadastro()
    {

        $pagamentosModel = new PagamentosModel();

        $nome = $this->request->getPost('nome');

        $data = [
            'nome' => $nome,
            'ativo' => 1
        ];

        $pagamentosModel->insert($data);

        return redirect()->to('pagamentos/')->with('Success', 'Cadastro feito com sucesso');
    }


    public function edita($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $pagamentosModel = new PagamentosModel();

        $data = [
            'pagamento' => $pagamentosModel->find($id),
        ];

        return view('/dashboard/cadastros/pagamentos/editar', $data);
    }

    public function editar($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $pagamentosModel = new PagamentosModel();

        $data = [
            'nome' => $this->request->getPost('nome'),
        ];

        $id = $pagamentosModel->update($id, $data);
        if ($id) {
            return redirect()->to('/pagamentos')->with('success', 'Cliente cadastrada com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
        }
    }

    public function excluir($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $pagamentosModel = new PagamentosModel();
        $pagamentosModel->find($id);

        $dados = [
            'ativo' => 0,
            'excluido' => 1
        ];

        $pagamentosModel->update($id, $dados);
        return redirect()->to('/pagamentos')->with('success', 'Cliente desativado com sucesso.');
    }
}
