<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CondicaoPagamentoModel;
use CodeIgniter\HTTP\ResponseInterface;

class CondicaoPagamento extends BaseController
{
   public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $condicaoModel = new CondicaoPagamentoModel();

        $pagina = $this->request->getVar('page') ?? 1;

        // Define o número de itens por página
        $itensPorPagina = 10;

        // Busca os dados paginados
        $condicao = $condicaoModel->where('excluido', 0)->paginate($itensPorPagina);

        // Gera os links de paginação automaticamente
        $paginacao = $condicaoModel->pager;

     

        $data = [
            'condicoes' => $condicao,
            'paginacao' => $paginacao,
        ];


        return view('/dashboard/cadastros/condicao/index', $data);
    }

    public function cadastrar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        return view('/dashboard/cadastros/condicao/cadastrar');
    }

    public function salvar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $condicaoModel = new CondicaoPagamentoModel();

        $data = [
            'nome' => $this->request->getPost('nome'),
            'ativo' => 1
        ];

        $id = $condicaoModel->insert($data);

        if (is_int($id)) {
            return redirect()->to('/condicao')->with('success', 'Cliente cadastrada com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
        }
    }

    public function edita($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $condicaoModel = new CondicaoPagamentoModel();

        $data = [
            'condicao' => $condicaoModel->find($id),
        ];

        return view('/dashboard/cadastros/condicao/editar', $data);
    }

    public function editar($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $condicaoModel = new CondicaoPagamentoModel();

        $data = [
            'nome' => $this->request->getPost('nome'),
        ];

        $id = $condicaoModel->update($id, $data);
        if ($id) {
            return redirect()->to('/condicao')->with('success', 'Cliente cadastrada com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
        }
    }

    public function excluir($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $condicaoModel = new CondicaoPagamentoModel();
        $condicaoModel->find($id);

        $dados = [
            'ativo' => 0,
            'excluido' => 1
        ];

        $condicaoModel->update($id, $dados);
        return redirect()->to('/condicao')->with('success', 'Cliente desativado com sucesso.');
    }

}
