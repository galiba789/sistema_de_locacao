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
        $locacoesModel = new LocacoesModel();
        $clientesModel = new Clientes();

        $pagina = $this->request->getVar('page') ?? 1;

        // Define o número de itens por página
        $itensPorPagina = 10;

        // Busca os dados paginados
        $locacoes = $locacoesModel->paginate($itensPorPagina);

        // Gera os links de paginação automaticamente
        $paginacao = $locacoesModel->pager;

        $locacoes = $locacoesModel->getAtivos();

        foreach ($locacoes as &$locacao) {
            $cliente = $clientesModel->find($locacao['cliente_id']);

            if ($cliente) {
                if ($cliente['tipo'] == 1) {
                    $locacao['cliente_nome'] = $cliente['nome'];
                } else if ($cliente['tipo'] == 2) {
                    $locacao['cliente_nome'] = $cliente['razao_social'];
                } else {
                    $locacao['cliente_nome'] = 'Tipo de cliente desconhecido';
                }
            } else {
                $locacao['cliente_nome'] = 'Desconhecido';
            }
        }

        $data = [
            'locacoes' => $locacoes,
            'paginacao' => $paginacao,
        ];

        return view('dashboard/locacoes/locacao/index', $data);
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
            ->with('contrato_id', $locacaoId);
    }
    public function edita($id)
    {
        $produtosModel = new ProdutosModel();
        $locacoesModel = new LocacoesModel();
        $produtosLocacoesModel = new LocacoesProdutosModel();
        $clientesModel = new Clientes();

        // Obtém os dados da locação
        $locacao = $locacoesModel->find($id);
        if (!$locacao) {
            return redirect()->to('/locacoes')->with('error', 'Locação não encontrada.');
        }
        $cliente = $clientesModel->find($locacao['cliente_id']);
        $locacao['cliente_nome'] = $cliente ? ($cliente['tipo'] == 1 ? $cliente['nome'] : $cliente['razao_social']) : 'Desconhecido';

        $cliente = $clientesModel->find($locacao['cliente_id']);
        $produtosLocacoes = $produtosLocacoesModel->getByLocacaoId($id);
        $produtoIds = array_column($produtosLocacoes, 'produto_id');
        $produto = !empty($produtoIds) ? $produtosModel->find($produtoIds) : [];

        $produtosLocacao = $produtosLocacoesModel->where('locacao_id', $id)->findAll();
        foreach ($produtosLocacao as &$produtoLocacao) {
            $produto = $produtosModel->find($produtoLocacao['produto_id']);
            if ($produto) {
                $produtoLocacao['nome'] = $produto['nome'];
                $produtoLocacao['preco_diaria'] = $produto['preco_diaria'];
            } else {
                $produtoLocacao['nome'] = 'Produto removido';
            }
        }
        $locacao['produtos'] = $produtosLocacao;

        // print_r($locacao['produtos']);
        // exit;
        $produtos = $produtosModel->getAtivos();



        $data = [
            'locacao' => $locacao,
            'cliente' => $cliente,
            'clientes' => $clientesModel->getAtivos(),
            'produtos' => $produtos
        ];

        return view('/dashboard/locacoes/locacao/editar', $data);
    }

    public function editar($id)
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
    
        // Atualizar a locação
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
    
        $locacoesModel->update($id, $dadosLocacao);
    
        // Atualizar produtos da locação
        $produtoIds = $this->request->getPost('produto_id');
        $quantidades = $this->request->getPost('quantidade');
        $precosDiaria = $this->request->getPost('preco_diaria');
        $totaisUnitarios = $this->request->getPost('total_unitario');
    
        // Remover produtos antigos associados à locação
        $produtosLocacoesModel->where('locacao_id', $id)->delete();
    
        // Adicionar os novos produtos corretamente
        foreach ($produtoIds as $index => $produtoId) {
            if (!empty($produtoId) && !empty($quantidades[$index])) {
                $dadosProdutoLocacao = [
                    'locacao_id'     => $id,
                    'produto_id'     => $produtoId,
                    'quantidade'     => $quantidades[$index],
                    'preco_diaria'   => $precosDiaria[$index],
                    'total_unitario' => $totaisUnitarios[$index]
                ];
                $produtosLocacoesModel->insert($dadosProdutoLocacao); // Correção aqui: usar insert() em vez de update()
            }
        }
    
        return redirect()->to('/locacoes')
            ->with('success', 'Locação atualizada com sucesso!');
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




    public function gerarContrato($id)
    {
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

    public function cancelarContrato($id){
        $locacaoModel= new LocacoesModel();
        
        $dados = [
            'situacao' => 5,
        ];
        $locacaoModel->update($id, $dados);

        return redirect()->to('/locacoes')
        ->with('success', 'Locação atualizada com sucesso!');
    }
}
