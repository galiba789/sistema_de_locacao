<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Clientes;
use App\Models\ConsultasCep;
use App\Models\LocacoesModel;
use App\Models\LocacoesProdutosModel;
use App\Models\OrcamentoModel;
use App\Models\ProdutosModel;
use App\Models\ProdutosOrcamentoModel;
use CodeIgniter\HTTP\ResponseInterface;

date_default_timezone_set('America/Sao_Paulo');
class Orcamento extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        
        $orcamentoModel = new OrcamentoModel();
        $clientesModel = new Clientes();

        $orcamentoModel->getAtivos();
        // Pega a página atual ou define 1 se não houver
        $pagina = $this->request->getVar('page') ?? 1;

        // Define o número de itens por página
        $itensPorPagina = 10;

        $clientes = $clientesModel->findAll();
        // Busca os dados paginados (mantém a variável locacoes paginada)
        $orcamentos = $orcamentoModel
            ->where('situacao !=', 5)
            ->orderBy('orcamento.created_at', 'DESC')
            ->paginate($itensPorPagina);

        // Gera os links de paginação automaticamente
        $paginacao = $orcamentoModel->pager;
        $clientesMap = [];
        foreach ($clientes as $cliente) {
            $clientesMap[$cliente['id']] = $cliente;
        }
        
        // Processa os dados de locação associando os nomes dos clientes
        foreach ($orcamentos as &$orcamento) {
            if (isset($clientesMap[$orcamento['cliente_id']])) {
                $cliente = $clientesMap[$orcamento['cliente_id']];
                $orcamento['tipo'] = $cliente['tipo']; // Garantimos que sempre terá um valor
                $orcamento['cliente_nome'] = $cliente['tipo'] == 1 ? $cliente['nome'] : $cliente['razao_social'];
            }
        }
        // print_r($orcamentos);
        // exit;

        // Prepara os dados para a view
        $data = [
            'orcamentos' => $orcamentos,
            'paginacao' => $paginacao,
        ];

        // Retorna a view com os dados
        return view('dashboard/orcamento/index', $data);
    }

    public function cadastrar()
    {
        $clienteModels = new Clientes();
        $produtosModels = new ProdutosModel();

        $data = [
            'clientes' => $clienteModels->getAtivos(),
            'produtos' => $produtosModels->getAtivos(),
        ];

        return view('dashboard/orcamento/cadastrar', $data);
    }
    public function salvar()
    {
        $orcamentoModel = new OrcamentoModel();
        $orcamentoProdutosModel = new ProdutosOrcamentoModel();
    
        $data_entrega = $this->request->getPost('data_entrega');
        $data_devolucao = $this->request->getPost('data_devolucao');
        $produtos = $this->request->getPost('produto_id');
        $quantidade_solicitada = $this->request->getPost('quantidade');
    
        // Validação simples para evitar inserções incorretas
        if (empty($produtos) || empty($quantidade_solicitada)) {
            return redirect()->back()->with('erro', 'Produtos e quantidades são obrigatórios.');
        }
    
        // Verifica a disponibilidade dos produtos
        $verificacao = $this->verificarDisponibilidade($produtos, $data_entrega, $data_devolucao, null, $quantidade_solicitada);
        if ($verificacao !== true) {
            return redirect()->back()->with('erro', $verificacao);
        }
    
        // Dados para salvar no orçamento (não inclui o 'id'!)
        $dadosOrcamento = [
            'cliente_id'      => $this->request->getPost('cliente_id'),
            'situacao'        => $this->request->getPost('situacao'),
            'data_entrega'    => $data_entrega,
            'data_devolucao'  => $data_devolucao,
            'total_diarias'   => $this->request->getPost('total_diarias'),
            'forma_pagamento' => $this->request->getPost('forma_pagamento'),
            'subtotal'        => $this->request->getPost('subtotal'),
            'desconto'        => $this->request->getPost('desconto'),
            'valor_total'     => $this->request->getPost('valor_total'),
            'observacao'      => $this->request->getPost('observacao'),
            'acessorios'      => $this->request->getPost('acessorios'),
            'created_at'      => date('Y-m-d H:i:s'), // Corrigi o formato da data para MySQL
        ];
    
        if (!$orcamentoModel->save($dadosOrcamento)) {
            return redirect()->back()->with('erro', 'Erro ao salvar o orçamento.');
        }
    
        $orcamento_id = $orcamentoModel->insertID(); // Captura o ID gerado
    
        if (!$orcamento_id) {
            return redirect()->back()->with('erro', 'Erro ao capturar o ID do orçamento.');
        }
    
        // Inserir os produtos vinculados ao orçamento
        $totaisUnitarios = $this->request->getPost('total_unitario');
    
        foreach ($produtos as $index => $produtoId) {
            if (!empty($produtoId) && isset($quantidade_solicitada[$index])) {
                $dadosProdutoOrcamento = [
                    'orcamento_id'   => $orcamento_id,
                    'produto_id'     => $produtoId,
                    'quantidade'     => $quantidade_solicitada[$index],
                    'total_unitario' => $totaisUnitarios[$index] ?? 0, // default para evitar erro
                ];
                if (!$orcamentoProdutosModel->save($dadosProdutoOrcamento)) {
                    // Se der erro ao inserir um produto, poderia logar ou lidar melhor aqui
                    return redirect()->back()->with('erro', 'Erro ao salvar produtos do orçamento.');
                }
            }
        }
    
        // Redireciona para a página de orçamentos
        return redirect()->to('/orcamento')
            ->with('success', 'Orçamento salvo com sucesso!')
            ->with('orcamento_id', $orcamento_id);
    }
    


    public function edita($id)
    {
        $produtosModel = new ProdutosModel();
        $orcamentoModel = new OrcamentoModel();
        $produtosOrcamentoModels = new ProdutosOrcamentoModel();
        $clientesModel = new Clientes();

        // Obtém os dados da locação
        $orcamento = $orcamentoModel->find($id);
        if (!$orcamento) {
            return redirect()->to('/orcamento')->with('error', 'Locação não encontrada.');
        }
        $orcamento = $orcamentoModel
            ->select('orcamento.*, clientes.nome AS cliente_nome, clientes.razao_social, clientes.tipo AS cliente_tipo')
            ->join('clientes', 'clientes.id = orcamento.cliente_id', 'left')
            ->where('orcamento.id', $id)
            ->first();

        // Define corretamente o nome do cliente considerando o tipo
        if ($orcamento) {
            $orcamento['cliente_nome'] = $orcamento['cliente_tipo'] == 1 ? $orcamento['cliente_nome'] : $orcamento['razao_social'];
        }


        // Buscar produtos da locação com JOIN para obter os detalhes de cada produto
        $produtos_orcamentos = $produtosOrcamentoModels
            ->select('orcamento_produtos.*, produtos.nome AS produto_nome, produtos.preco_diaria')
            ->join('produtos', 'produtos.id = orcamento_produtos.produto_id', 'left')
            ->where('orcamento_produtos.orcamento_id', $id)
            ->findAll();

        // Se um produto foi removido, definir nome como "Produto removido"
        foreach ($produtos_orcamentos as &$produto_orcamento) {
            if (!$produto_orcamento['produto_nome']) {
                $produto_orcamento['produto_nome'] = 'Produto removido';
            }
        }

        $orcamento['produtos'] = $produtos_orcamentos;
        $produtos = $produtosModel->getAtivos();


        // print_r($orcamento);
        // exit;
        $data = [
            'orcamento' => $orcamento,
            'clientes' => $clientesModel->getAtivos(),
            'produtos' => $produtos
        ];

        return view('/dashboard/orcamento/editar', $data);
    }

    public function editar($id)
    {
        $orcamentoModel = new OrcamentoModel();
        $produtosOrcamentoModels = new ProdutosOrcamentoModel();

        $data_entrega = $this->request->getPost('data_entrega');
        $data_devolucao = $this->request->getPost('data_devolucao');
        $produtos = $this->request->getPost('produto_id') ?? [];
        $quantidade_solicitada = $this->request->getPost('quantidade');

        if (!is_array($produtos) || empty($produtos)) {
            return redirect()->back()->with('erro', 'Nenhum produto selecionado.');
        }

        // Verifica a disponibilidade dos produtos
        $verificacao = $this->verificarDisponibilidade($produtos, $data_entrega, $data_devolucao, $id, $quantidade_solicitada);

        if ($verificacao !== true) {
            return redirect()->back()->with('erro', $verificacao);
        }

        // Atualizar a locação
        $dadosOrcamento = [
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
            'acessorios'      => $this->request->getPost('acessorios'),
            'created_at' => date('Y/m/d H:i:s'),
        ];

        $orcamentoModel->update($id, $dadosOrcamento);

        // Atualizar produtos da locação
        $produtoIds = $this->request->getPost('produto_id') ?? [];
        $quantidades = $this->request->getPost('quantidade') ?? [];
        $precosDiaria = $this->request->getPost('preco_diaria') ?? [];
        $totaisUnitarios = $this->request->getPost('total_unitario') ?? [];

        if ($id) {
            // Remover produtos antigos associados à locação
            $produtosOrcamentoModels->where('orcamento_id', $id)->delete();
        }

        // Adicionar os novos produtos corretamente
        foreach ($produtoIds as $index => $produtoId) {
            if (!empty($produtoId) && !empty($quantidades[$index])) {
                $dadosProdutoLocacao = [
                    'orcamento_id'     => $id,
                    'produto_id'     => $produtoId,
                    'quantidade'     => $quantidades[$index],
                    'preco_diaria'   => $precosDiaria[$index],
                    'total_unitario' => $totaisUnitarios[$index]
                ];
                $produtosOrcamentoModels->insert($dadosProdutoLocacao);
            }
        }

        return redirect()->to('/orcamento')
            ->with('success', 'Locação atualizada com sucesso!');
    }

    public function verificarDisponibilidade($produtos, $data_entrega, $data_devolucao, $locacao_id, $quantidade_solicitada)
    {
        $produtosModels = new ProdutosModel();
        $locacoesProdutoModel = new LocacoesProdutosModel();
        $data_entrega   = date('Y-m-d H:i:s', strtotime($data_entrega));
        $data_devolucao = date('Y-m-d H:i:s', strtotime($data_devolucao));

        foreach ($produtos as $index => $produto_id) {
            $produto_info = $produtosModels->where('id', $produto_id)->get()->getRow();

            if ($produto_info) {
                $nome_produto      = $produto_info->nome;
                $quantidade_estoque = $produto_info->quantidade;

                $query = $locacoesProdutoModel
                    ->selectSum('locacoes_produtos.quantidade', 'quantidade_alocada')
                    ->join('locacao', 'locacao.id = locacoes_produtos.locacao_id')
                    ->where('locacoes_produtos.produto_id', $produto_id)
                    ->where('locacao.data_entrega <=', $data_devolucao)
                    ->where('locacao.data_devolucao >=', $data_entrega)
                    ->groupBy('locacoes_produtos.produto_id');

                if ($locacao_id) {
                    $query->where('locacao.id !=', $locacao_id);
                }

                $result = $query->get()->getRow();
                $quantidade_alocada = $result ? (int)$result->quantidade_alocada : 0;
                $quantidade_requerida = $quantidade_solicitada[$index] ?? 1;
                // print_r($quantidade_requerida);
                // exit;
                $quantidade_disponivel = $quantidade_estoque - $quantidade_alocada;

                if ($quantidade_requerida > $quantidade_disponivel) {
                    return "O produto '{$nome_produto}' não está disponível na quantidade solicitada para as datas informadas. Disponível: {$quantidade_disponivel}.";
                }
            } else {
                return "Produto com ID '{$produto_id}' não encontrado.";
            }
        }
        return true;
    }

    public function cancelarOrcamento($id)
    {
        $orcamentoModel = new OrcamentoModel();

        $dados = [
            'situacao' => 5,
        ];
        $orcamentoModel->update($id, $dados);

        return redirect()->to('/orcamento')
            ->with('success', 'Locação atualizada com sucesso!');
    }

    public function buscar()
    {
        $tipo = $this->request->getGet('tipo');
        $palavra = $this->request->getGet('palavra');
        $situacao = $this->request->getGet('situacao');

        $orcamentoModel = new OrcamentoModel();
        $builder = $orcamentoModel->select('orcamento.*, clientes.nome as cliente_nome, clientes.razao_social as cliente_razao_social')
            ->join('clientes', 'clientes.id = orcamento.cliente_id');

        // Filtrar locações canceladas apenas se a opção foi selecionada
        if (!empty($situacao)) {
            $builder->where('orcamento.situacao', $situacao);
        } else {
            $builder->where('orcamento.situacao !=', 5); // Excluir canceladas por padrão
        }

        // Filtros de busca por palavra-chave
        if (!empty($palavra)) {
            switch ($tipo) {
                case '1': // Buscar por Data
                    if (strtotime($palavra)) {
                        $builder->where('DATE(orcamento.created_at)', date('Y-m-d', strtotime($palavra)));
                    }
                    break;
                case '2': // Buscar por Nome do Cliente
                    $builder->like('clientes.nome', $palavra);
                    break;
                case '3': // Buscar por Razão Social
                    $builder->like('clientes.razao_social', $palavra);
                    break;
                case '4': // Buscar por Código da Locação
                    $builder->where('orcamento.id', $palavra);
                    break;
            }
        }

        // Ordenar em ordem crescente pela data de criação
        $builder->orderBy('orcamento.created_at', 'DESC');

        $orcamentos = $builder->findAll();

        // Formatação de datas e valores
        foreach ($orcamentos as &$orcamento) {
            if (isset($orcamento['created_at'])) {
                $orcamento['created_at'] = date('d/m/Y H:i:s', strtotime($orcamento['created_at']));
            }
            if (isset($orcamento['data_entrega'])) {
                $orcamento['data_entrega'] = date('d/m/Y H:i:s', strtotime($orcamento['data_entrega']));
            }
            if (isset($orcamento['data_devolucao'])) {
                $orcamento['data_devolucao'] = date('d/m/Y H:i:s', strtotime($orcamento['data_devolucao']));
            }
            if (isset($orcamento['valor_total'])) {
                $orcamento['valor_total'] = number_format($orcamento['valor_total'], 2, ',', '.');
            }
        }

        return $this->response->setJSON($orcamentos);
    }
    public function consulta()
    {
        $cep = $this->request->getPost('cep');

        $Consulta = new ConsultasCep();

        echo $Consulta->consulta($cep);
    }

    public function salvarClientes()
    {
        $clientesModel = new Clientes();
        $tipo = $this->request->getPost('type');
    
        if ($tipo == 1) {
            $data = [
                'tipo' => $tipo,
                'nome' => $this->request->getPost('nome'),
                'cpf' => $this->request->getPost('cpf'),
                'rg' => $this->request->getPost('rg'),
                'email' => $this->request->getPost('email'),
                'telefone_contato' => $this->request->getPost('telefone_contato_fisica'),
                'nascimento' => $this->request->getPost('nascimento'),
                'obs' => $this->request->getPost('obs'),
                // Endereço
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
                // Endereço
                'cep' => $this->request->getPost('cep'),
                'logradouro' => $this->request->getPost('logradouro'),
                'numero' => $this->request->getPost('numero'),
                'complemento' => $this->request->getPost('complemento'),
                'bairro' => $this->request->getPost('bairro'),
                'estado' => $this->request->getPost('estado'),
                'localidade' => $this->request->getPost('localidade'),
            ];
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tipo inválido de cliente.']);
        }
    
        $clientesModel->insert($data);
        $data['id'] = $clientesModel->insertID(); // Pega o ID gerado
    
        return $this->response->setJSON(['status' => 'success', 'cliente' => $data]);
    }
    
    public function verificarDisponibilidadeAjax()
    {
        $produto_id = $this->request->getPost('produto_id');
        $data_entrega = $this->request->getPost('data_entrega');
        $data_devolucao = $this->request->getPost('data_devolucao');
        $quantidade_solicitada = $this->request->getPost('quantidade');

        if (!$produto_id || !$data_entrega || !$data_devolucao) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Dados incompletos.'
            ]);
        }
        // Chama a função para verificar a disponibilidade
        $disponivel = $this->verificarDisponibilidade([$produto_id], $data_entrega, $data_devolucao, $orcamento_id = null, $quantidade_solicitada);

        if ($disponivel === true) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Produto disponível.'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $disponivel
            ]);
        }
    }

    public function fazerContrato($id)
    {
        $orcamentoModel = new OrcamentoModel();
        $orcamentoProdutosModel = new ProdutosOrcamentoModel();
        $locacaoModel = new LocacoesModel();
        $produtoLocacaoModel = new LocacoesProdutosModel();

        // 1. Buscar orçamento
        $orcamento = $orcamentoModel->find($id);
        if (!$orcamento) {
            return false; // Orçamento não encontrado
        }

        // 2. Buscar produtos do orçamento
        $orcamentoProdutos = $orcamentoProdutosModel->where('orcamento_id', $id)->findAll();

        // 3. Criar nova locação
        $novaLocacao = [
            'cliente_id'    => $orcamento['cliente_id'],
            'descricao'     => $orcamento['descricao'],
            'situacao'      => $orcamento['situacao'],
            'total_diarias' => $orcamento['total_diarias'],
            'desconto'      => $orcamento['desconto'],
            'observação'    => $orcamento['observacao'],
            'acessorios'    => $orcamento['acessorios'],
            'valor_total'   => $orcamento['valor_total'],
            'data_entrega'  => $orcamento['data_entrega'],
            'data_devolucao' => $orcamento['data_devolucao'],
            'forma_pagamento' => $orcamento['forma_pagamento'],
            'condicao'      => $orcamento['condicao'],
            'status'        => 1
        ];

        $locacaoId = $locacaoModel->insert($novaLocacao, true); // true retorna o ID inserido

        if (!$locacaoId) {
            return redirect()->back()
                ->with('Error', 'Erro');
        }

        // 4. Adicionar os produtos do orçamento à locação
        foreach ($orcamentoProdutos as $produto) {
            $produtoLocacaoModel->insert([
                'locacao_id'    => $locacaoId,
                'produto_id'    => $produto['produto_id'],
                'quantidade'    => $produto['quantidade'],
            ]);
        }

        // 5. Atualizar status do orçamento
        $orcamentoModel->update($id, ['situacao' => 4]);


        return redirect()->to('/locacoes')
            ->with('success', 'Locação atualizada com sucesso!');
    }


    public function gerarContrato($id)
    {
        $orcamentoModel = new OrcamentoModel();
        $produtoOrcamentoModel = new ProdutosOrcamentoModel();
        $clienteModel = new clientes();

        $orcamento = $orcamentoModel->find($id);
        if (!$orcamento) {
            return redirect()->back()->with('error', 'Orçamento não encontrado.');
        }

        $orcamentoProdutos = $produtoOrcamentoModel
            ->join('produtos P', 'P.id = orcamento_produtos.produto_id')
            ->select('orcamento_produtos.*, P.nome, P.numero_serie, P.acessorios ')
            ->where('orcamento_produtos.orcamento_id', $orcamento['id'])
            ->findAll();

        $cliente = $clienteModel->find($orcamento['cliente_id']);

        $dados = [
            'orcamento' => $orcamento,
            'orcamento_produtos' => $orcamentoProdutos,
            'cliente' => $cliente,
        ];

        return view('dashboard/orcamento/contrato', $dados);
    }
}
