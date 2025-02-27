<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;
use CodeIgniter\HTTP\ResponseInterface;

class Categorias extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $categoriaModel = new CategoriaModel();

        $pagina = $this->request->getVar('page') ?? 1;

        // Define o número de itens por página
        $itensPorPagina = 10;

        // Busca os dados paginados
        $categorias = $categoriaModel->paginate($itensPorPagina);

        // Gera os links de paginação automaticamente
        $paginacao = $categoriaModel->pager;
        
        
        $data = [
            'categorias' => $categoriaModel->getAtivos(),
            'paginacao' => $paginacao,
        ];


        return view('/dashboard/cadastros/categorias/index', $data);
    }

    public function cadastrar(){
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        return view('/dashboard/cadastros/categorias/cadastrar');
    }

    public function salvar(){
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $categoriaModel = new CategoriaModel();

        $data = [
            'nome' => $this->request->getPost('nome'),
        ];
        
        $id = $categoriaModel->insert($data);
        
        if (is_int($id)) {
            return redirect()->to('/categorias')->with('success', 'Cliente cadastrada com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
        }

    }

    public function edita($id) {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $categoriaModel = new CategoriaModel();
        
        $data = [
            'categoria' => $categoriaModel->getById($id),
        ];

        return view('/dashboard/cadastros/categorias/editar', $data);
    }

    public function editar($id) {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $categoriaModel = new CategoriaModel();
        
        $data = [
            'nome' => $this->request->getPost('nome'),
        ];

        $id = $categoriaModel->update($id, $data);
        if ($id) {
            return redirect()->to('/categorias')->with('success', 'Cliente cadastrada com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
        }
    }

    public function excluir($id){
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $categoriaModel = new CategoriaModel();
        $categoriaModel->find($id);

        $dados = [
            'status' => 0,
        ];

        $categoriaModel->update($id, $dados);
        return redirect()->to('/categorias')->with('success', 'Cliente desativado com sucesso.');
    }
}
