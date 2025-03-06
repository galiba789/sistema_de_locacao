<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Clientes;
use App\Models\ConsultasCep;
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
    
        // Pega a página atual ou define 1 se não houver
        $pagina = $this->request->getVar('page') ?? 1;
    
        // Define o número de itens por página
        $itensPorPagina = 10;
    
        // Busca os dados paginados (mantém a variável locacoes paginada)
        $locacoes = $locacoesModel->paginate($itensPorPagina);
    
        // Gera os links de paginação automaticamente
        $paginacao = $locacoesModel->pager;
    
        // Processa os dados de locação com os nomes dos clientes
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
    
        // Prepara os dados para a view
        $data = [
            'locacoes' => $locacoes,
            'paginacao' => $paginacao,
        ];
    
        // Retorna a view com os dados
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
        $locacao = $locacoesModel
            ->select('locacao.*, clientes.nome AS cliente_nome, clientes.razao_social, clientes.tipo AS cliente_tipo')
            ->join('clientes', 'clientes.id = locacao.cliente_id', 'left')
            ->where('locacao.id', $id)
            ->first();

        // Define corretamente o nome do cliente considerando o tipo
        if ($locacao) {
            $locacao['cliente_nome'] = $locacao['cliente_tipo'] == 1 ? $locacao['cliente_nome'] : $locacao['razao_social'];
        }


        // Buscar produtos da locação com JOIN para obter os detalhes de cada produto
        $produtosLocacao = $produtosLocacoesModel
            ->select('locacoes_produtos.*, produtos.nome AS produto_nome, produtos.preco_diaria')
            ->join('produtos', 'produtos.id = locacoes_produtos.produto_id', 'left')
            ->where('locacoes_produtos.locacao_id', $id)
            ->findAll();

        // Se um produto foi removido, definir nome como "Produto removido"
        foreach ($produtosLocacao as &$produtoLocacao) {
            if (!$produtoLocacao['produto_nome']) {
                $produtoLocacao['produto_nome'] = 'Produto removido';
            }
        }

        $locacao['produtos'] = $produtosLocacao;


        // print_r($locacao);
        // exit;
        $produtos = $produtosModel->getAtivos();



        $data = [
            'locacao' => $locacao,
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
    $produtos = $this->request->getPost('produto_id') ?? [];

    if (!is_array($produtos) || empty($produtos)) {
        return redirect()->back()->with('erro', 'Nenhum produto selecionado.');
    }

    // Verifica a disponibilidade dos produtos
    $verificacao = $this->verificarDisponibilidade($produtos, $data_entrega, $data_devolucao, $id);

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
    $produtoIds = $this->request->getPost('produto_id') ?? [];
    $quantidades = $this->request->getPost('quantidade') ?? [];
    $precosDiaria = $this->request->getPost('preco_diaria') ?? [];
    $totaisUnitarios = $this->request->getPost('total_unitario') ?? [];

    if ($id) {
        // Remover produtos antigos associados à locação
        $produtosLocacoesModel->where('locacao_id', $id)->delete();
    }

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
            $produtosLocacoesModel->insert($dadosProdutoLocacao);
        }
    }

    return redirect()->to('/locacoes')
        ->with('success', 'Locação atualizada com sucesso!');
}



    public function verificarDisponibilidade($produtos, $data_entrega, $data_devolucao, $locacao_id = null)
    {
        $produtosModels = new ProdutosModel();
        $locacoesProdutoModel = new LocacoesProdutosModel();
        $data_entrega = date('Y-m-d H-i-s', strtotime($data_entrega));
        $data_devolucao = date('Y-m-d H-i-s', strtotime($data_devolucao));
    
        foreach ($produtos as $produto_id) {
            $quantidade_solicitada = 1;
    
            $produto_info = $produtosModels->where('id', $produto_id)->get()->getRow();
    
            if ($produto_info) {
                $nome_produto = $produto_info->nome;
                $quantidade_estoque = $produto_info->quantidade;
    
                $query = $locacoesProdutoModel
                    ->join('locacao', 'locacao.id = locacoes_produtos.locacao_id')
                    ->where('locacoes_produtos.produto_id', $produto_id)
                    ->where('locacao.data_entrega <=', $data_devolucao)
                    ->where('locacao.data_devolucao >=', $data_entrega);
    
                // Se for edição, ignora a locação atual
                if ($locacao_id) {
                    $query->where('locacao.id !=', $locacao_id);
                }
    
                $query->selectSum('locacoes_produtos.quantidade', 'quantidade_alocada');
                $result = $query->get()->getRow();
    
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

        $locacaoProdutos = $locacoesProdutoModel
            ->join('produtos P', 'P.id = locacoes_produtos.produto_id')
            ->select('locacoes_produtos.*, P.nome, P.numero_serie')
            ->where('locacoes_produtos.locacao_id', $locacao['id'])
            ->findAll();
        $cliente = $clienteModel->find($locacao['cliente_id']);
        // print_r($locacaoProdutos);
        // exit;


        $dados = [
            'locacao' => $locacao,
            'locacao_produtos' => $locacaoProdutos,
            'cliente' => $cliente,
        ];

        return view('dashboard/locacoes/locacao/contrato', $dados);
    }


    public function cancelarContrato($id)
    {
        $locacaoModel = new LocacoesModel();

        $dados = [
            'situacao' => 5,
        ];
        $locacaoModel->update($id, $dados);

        return redirect()->to('/locacoes')
            ->with('success', 'Locação atualizada com sucesso!');
    }
    public function buscar()
    {
        $tipo = $this->request->getGet('tipo');
        $palavra = $this->request->getGet('palavra');
        $situacao = $this->request->getGet('situacao');

        $locacoesModel = new LocacoesModel();
        $builder = $locacoesModel->select('locacao.*, clientes.nome as cliente_nome');
        $builder = $locacoesModel->select('locacao.*, clientes.razao_social as cliente_razao_social')
            ->join('clientes', 'clientes.id = locacao.cliente_id');
        if (!empty($situacao)) {
            $builder->where('locacao.situacao', $situacao);
        }


        if (!empty($palavra)) {
            switch ($tipo) {
                case '1': // Data
                    if (strtotime($palavra)) {
                        $builder->where('DATE(locacao.created_at)', date('Y-m-d H-i-s', strtotime($palavra)));
                    }
                    break;
                case '2': // Nome
                    $builder->like('clientes.nome', $palavra);
                    break;
                case '3': //razao Social
                    $builder->like('clientes.razao_social', $palavra);
                    break;
                case '4': // Código
                    $builder->where('locacao.id', $palavra);
                    break;
            }
        }

        $locacoes = $builder->findAll();

        foreach ($locacoes as &$locacao) {
            if (isset($locacao['created_at'])) {
                $locacao['created_at'] = date('d/m/Y H:i:s', strtotime($locacao['created_at']));
            }
            if (isset($locacao['data_entrega'])) {
                $locacao['data_entrega'] = date('d/m/Y H:i:s', strtotime($locacao['data_entrega']));
            }
            if (isset($locacao['data_devolucao'])) {
                $locacao['data_devolucao'] = date('d/m/Y H:i:s', strtotime($locacao['data_devolucao']));
            }

            if (isset($locacao['valor_total'])) {
                $locacao['valor_total'] = number_format($locacao['valor_total'], 2, ',', '.');
            }
        }
        
        return $this->response->setJSON($locacoes);
    }
    public function consulta()
	{
        $cep = $this->request->getPost('cep');
        
        $Consulta = new ConsultasCep();

        echo $Consulta->consulta($cep);
    }

    public function salvarClientes(){
        $clientesModel = new Clientes();
    
        $tipo = $this->request->getPost('type'); 
    
        if ($tipo == 1) { 
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
                'telefone' => $this->request->getPost('telefone_contato'),
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
    
        $clientesModel->insert($data);

        return redirect()->to('/locacoes/cadastrar');
    }
}
