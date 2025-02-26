<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Clientes;
use App\Models\LocacoesModel;
use App\Models\LocacoesProdutosModel;
use App\Models\ProdutosModel;
use CodeIgniter\HTTP\ResponseInterface;

class Locacoes extends BaseController
{
    public function index()
    {

        return view('dashboard/locacoes/locacao/index');
    }

    public function cadastrar()
    {
        $clienteModels = new Clientes();
        $produtosModels = new ProdutosModel();

        $data = [
            'clientes' => $clienteModels->getAtivos(),
            'produtos' => $produtosModels->getAtivos(),
        ];

        return view('dashboard/locacoes/locacao/cadastrar', $data);
    }

    public function salvar()
    {
        $locacoesModel = new LocacoesModel();
        $produtosLocacoesModel = new LocacoesProdutosModel();
    
        $data_entrega = $this->request->getPost('data_entrega');
        $data_devolucao = $this->request->getPost('data_devolucao');
        $produtos = $this->request->getPost('produto_id');
    
        // Verifica a disponibilidade dos produtos
        $verificacao = $this->verificarDisponibilidade($produtos, $data_entrega, $data_devolucao);
    
        if ($verificacao !== true) {
            return redirect()->back()->with('erro', $verificacao);
        }
    
        // Inserir locação e obter o ID gerado
        $dadosLocacao = [
            'cliente_id'      => $this->request->getPost('cliente_id'),
            'situacao'        => $this->request->getPost('situacao'),
            'data_entrega'    => $data_entrega,
            'data_devolucao'  => $data_devolucao,
            'total_diarias'   => $this->request->getPost('total_diarias'),
            'condicao'        => $this->request->getPost('condicao'),
            'forma_pagamento' => $this->request->getPost('forma_pagamento'),
            'subtotal'        => $this->request->getPost('subtotal'),
            'desconto'        => $this->request->getPost('desconto'),
            'valor_total'     => $this->request->getPost('valor_total'),
            'observacao'      => $this->request->getPost('observacao'),
        ];
    
        $locacoesModel->insert($dadosLocacao);
        $locacaoId = $locacoesModel->insertID(); // Obtendo o ID da locação recém-criada
    
        // Inserir os produtos da locação
        $produtoIds = $this->request->getPost('produto_id');
        $quantidades = $this->request->getPost('quantidade');
        $precosDiaria = $this->request->getPost('preco_diaria');
        $totaisUnitarios = $this->request->getPost('total_unitario');
    
        foreach ($produtoIds as $index => $produtoId) {
            if (!empty($produtoId) && !empty($quantidades[$index])) {
                $dadosProdutoLocacao = [
                    'locacao_id'    => $locacaoId,
                    'produto_id'    => $produtoId,
                    'quantidade'    => $quantidades[$index],
                    'preco_diaria'  => $precosDiaria[$index],
                    'total_unitario' => $totaisUnitarios[$index]
                ];
                $produtosLocacoesModel->insert($dadosProdutoLocacao);
            }
        }
    
        if (!$locacaoId) {
            return redirect()->back()->with('error', 'Erro ao salvar locação!');
        }
    
        // Redireciona para /locacoes e passa o ID da locação recém-criada
        return redirect()->to('/locacoes')
            ->with('success', 'Locação salva com sucesso!')
            ->with('contrato_id', $locacaoId); // Aqui estava o erro: agora estamos passando $locacaoId
    }    

    public function verificarDisponibilidade($produtos, $data_entrega, $data_devolucao)
    {
        $produtosModels = new ProdutosModel();
        $locacoesProdutoModel = new LocacoesProdutosModel();
        $data_entrega = date('Y-m-d', strtotime($data_entrega));
        $data_devolucao = date('Y-m-d', strtotime($data_devolucao));
    
        foreach ($produtos as $produto_id) {
            $quantidade_solicitada = 1;  
    
            $produto_info = $produtosModels
                ->where('id', $produto_id)
                ->get()
                ->getRow();
    
            if ($produto_info) {
                $nome_produto = $produto_info->nome;  
                $quantidade_estoque = $produto_info->quantidade; 
    
                $query = $locacoesProdutoModel
                    ->join('locacao', 'locacao.id = locacoes_produtos.locacao_id')
                    ->where('locacoes_produtos.produto_id', $produto_id)
                    ->where('locacao.data_entrega <=', $data_devolucao)
                    ->where('locacao.data_devolucao >=', $data_entrega)
                    ->selectSum('locacoes_produtos.quantidade', 'quantidade_alocada')
                    ->get();
    
               
                $result = $query->getRow();
    
                $quantidade_alocada = $result ? $result->quantidade_alocada : 0;
    
               
                if ($quantidade_alocada + $quantidade_solicitada > $quantidade_estoque) {
                
                    return "O produto '{$nome_produto}' não está disponível na quantidade solicitada para as datas informadas.";
                }
            } else {
             
                return "Produto com ID '{$produto_id}' não encontrado.";
            }
        }
    

        return true;
    }
    
    public function gerarContrato($id){
        $locacoesModel = new LocacoesModel();
        $locacoesProdutoModel = new LocacoesProdutosModel();
        $produtoModel = new ProdutosModel();
        $clienteModel = new Clientes();
        
        $locacao = $locacoesModel->find($id);

        $locacaoProdutos = $locacoesProdutoModel->getByLocacaoId($locacao['id']);

        $cliente = $clienteModel->find($locacao['cliente_id']);

        $produtoIds = array_column($locacaoProdutos, 'produto_id');

        $produtos = !empty($produtoIds) ? $produtoModel->find($produtoIds) : [];

        // print_r($produtoIds);
        // print_r($produtos);
        // print_r($cliente);
        // exit;

        $dados = [
            'locacao' => $locacao,
            'locacao_produto' => $locacaoProdutos,
            'produtos' => $produtos,
            'cliente' => $cliente,
        ];

        return view('dashboard/locacoes/locacao/contrato', $dados);
    }
}
