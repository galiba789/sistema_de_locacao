<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CategoriaModel;
use App\Models\ProdutosModel;
use CodeIgniter\HTTP\ResponseInterface;

class Produtos extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $produtosModel = new ProdutosModel();

        // Pega a página atual ou define 1 se não houver
        $pagina = $this->request->getVar('page') ?? 1;

        // Define o número de itens por página
        $itensPorPagina = 10;

        // Busca os dados paginados (mantém a variável produtos paginada)
        $produtos = $produtosModel->orderBy('produtos.id', 'DESC')->where('produtos.status !=', 0)
            ->paginate($itensPorPagina);


        // Gera os links de paginação automaticamente
        $paginacao = $produtosModel->pager;

        $data = [
            'produtos' => $produtos,
            'paginacao' => $paginacao,
        ];

        return view('/dashboard/cadastros/produtos/index', $data);
    }


    public function cadastrar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $categoriasModel = new CategoriaModel();

        $data = [
            'categorias' => $categoriasModel->getAtivos(),
        ];

        return view('/dashboard/cadastros/produtos/cadastrar', $data);
    }

    public function salvar()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $produtosModel = new ProdutosModel();

        $data = [
            'nome' => $this->request->getPost('nome'),
            'numero_serie' => $this->request->getPost('numero_serie'),
            'sku' => $this->request->getPost('sku'),
            'categoria_id' => $this->request->getPost('categoria_id'),
            'preco_diaria' => $this->request->getPost('preco_diaria'),
            'valor_minimo' => $this->request->getPost('valor_minimo'),
            'quantidade' => $this->request->getPost('quantidade'),
            'obs' => $this->request->getPost('obs'),
            'acessorios' => $this->request->getPost('acessorios'),
            'aditivo_contratual' => $this->request->getPost('aditivo_contratual'),
        ];

        $id = $produtosModel->insert($data);
        if (is_int($id)) {
            return redirect()->to('/produtos')->with('success', 'Cliente cadastrada com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
        }
    }

    public function edita($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $produtosModel = new ProdutosModel();
        $categoriasModel = new CategoriaModel();

        $data = [
            'produto' => $produtosModel->find($id),
            'categorias' => $categoriasModel->getAtivos(),
        ];
        return view('dashboard/cadastros/produtos/editar', $data);
    }

    public function editar($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $produtosModel = new ProdutosModel();
        $produtosModel->find($id);

        $data = [
            'nome' => $this->request->getPost('nome'),
            'numero_serie' => $this->request->getPost('numero_serie'),
            'sku' => $this->request->getPost('sku'),
            'categoria_id' => $this->request->getPost('categoria_id'),
            'preco_diaria' => $this->request->getPost('preco_diaria'),
            'valor_minimo' => $this->request->getPost('valor_minimo'),
            'quantidade' => $this->request->getPost('quantidade'),
            'obs' => $this->request->getPost('obs'),
            'acessorios' => $this->request->getPost('acessorios'),
            'aditivo_contratual' => $this->request->getPost('aditivo_contratual'),
        ];

        $id = $produtosModel->update($id, $data);
        if ($id) {
            return redirect()->to('/produtos')->with('success', 'Cliente cadastrada com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Erro ao cadastrar cliente.');
        }
    }

    public function excluir($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $produtosModel = new ProdutosModel();
        $produtosModel->find($id);

        $dados = [
            'status' => 1,
        ];

        $produtosModel->update($id, $dados);
        return redirect()->to('/produtos')->with('success', 'Cliente desativado com sucesso.');
    }

    public function buscar()
    {
        $nome = $this->request->getGet('nome');

        $produtosModel = new ProdutosModel();

        if (empty($nome)) {
            $produtos = $produtosModel->findAll();
        } else {
            $produtos = $produtosModel->like('nome', $nome, 'both')->findAll();
        }

        return $this->response->setJSON($produtos);
    }

    public function salvarCategoria()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Usuário não logado']);
        }

        $categoriasModel = new CategoriaModel();

        $dadosRecebidos = $this->request->getJSON(true); // Pega o JSON enviado

        if (!isset($dadosRecebidos['nome']) || empty(trim($dadosRecebidos['nome']))) {
            return $this->response->setJSON(['success' => false, 'message' => 'Nome da categoria é obrigatório']);
        }

        $nome = trim($dadosRecebidos['nome']);

        // Você pode colocar uma validação extra para não cadastrar nomes duplicados, se quiser

        $data = [
            'nome' => $nome,
            'ativo' => 1 // Se você usa controle de ativo/inativo
        ];

        $id = $categoriasModel->insert($data);

        if (is_int($id) || is_numeric($id)) {
            return $this->response->setJSON([
                'success' => true,
                'categoria' => [
                    'id' => $id,
                    'nome' => $nome
                ]
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Erro ao salvar a categoria']);
        }
    }
}
