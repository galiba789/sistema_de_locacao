<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Clientes;
use App\Models\ConsultasCep;
use App\Models\LocacoesModel;
use App\Models\LocacoesProdutosModel;
use App\Models\ProdutosModel;
use CodeIgniter\HTTP\ResponseInterface;

date_default_timezone_set('America/Sao_Paulo');

class Locacoes extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }
        $locacoesModel = new LocacoesModel();
        $clientesModel = new Clientes();

        $locacoesModel->getAtivos();
        // Pega a página atual ou define 1 se não houver
        $pagina = $this->request->getVar('page') ?? 1;

        // Define o número de itens por página
        $itensPorPagina = 10;

        // Busca os dados paginados (mantém a variável locacoes paginada)
        $locacoes = $locacoesModel
            ->where('situacao !=', 5)
            ->orderBy('locacao.created_at', 'DESC')
            ->paginate($itensPorPagina);

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
        $quantidade_solicitada = $this->request->getPost('quantidade');
        // Verifica a disponibilidade dos produtos
        $verificacao = $this->verificarDisponibilidade($produtos, $data_entrega, $data_devolucao, $locacao_id = null, $quantidade_solicitada);
        // print_r($verificacao);
        // exit;
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
            'acessorios'      => $this->request->getPost('acessorios'),
            'created_at' => date('Y/m/d H:i:s'),
        ];

        $locacoesModel->insert($dadosLocacao);
        $locacaoId = $locacoesModel->insertID(); // Obtendo o ID da locação recém-criada

        // Inserir os produtos da locação
        $produtoIds = $this->request->getPost('produto_id');
        $quantidades = $this->request->getPost('quantidade');
        $precosDiaria = $this->request->getPost('preco_diaria');
        // print_r($precosDiaria[0]);
        // exit;
        $totaisUnitarios = $this->request->getPost('total_unitario');

        foreach ($produtoIds as $index => $produtoId) {
            if (!empty($produtoId) && !empty($quantidades[$index])) {
                $dadosProdutoLocacao = [
                    'locacao_id'    => $locacaoId,
                    'produto_id'    => $produtoId,
                    'quantidade'    => $quantidades[$index],
                    'preco_diaria'  => $precosDiaria[$index],
                    'sub_total' => $totaisUnitarios[$index]
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
            ->select('locacoes_produtos.*, 
                  produtos.nome AS produto_nome, 
                  produtos.preco_diaria AS preco_produto_original')
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
        $quantidade_solicitada = $this->request->getPost('quantidade');

        if (!is_array($produtos) || empty($produtos)) {
            return redirect()->back()->with('erro', 'Nenhum produto selecionado.');
        }

        // // Verifica a disponibilidade dos produtos
        // $verificacao = $this->verificarDisponibilidade($produtos, $data_entrega, $data_devolucao, $id, $quantidade_solicitada);

        // if ($verificacao !== true) {
        //     return redirect()->back()->with('erro', $verificacao);
        // }

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
            'acessorios'      => $this->request->getPost('acessorios'),
            'created_at' => date('Y/m/d H:i:s'),
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
                // print_r($precosDiaria[$index]);
                // exit;
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
                    ->where('locacao.situacao != ', 4)
                    ->where('locacao.situacao != ', 5)
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
    public function gerarContrato($id)
    {
        $locacoesModel = new LocacoesModel();
        $locacoesProdutoModel = new LocacoesProdutosModel();
        $produtoModel = new ProdutosModel();
        $clienteModel = new Clientes();

        $locacao = $locacoesModel->find($id);
        // Simples da join, pega as infos que precisa e retorna pra view
        $locacaoProdutos = $locacoesProdutoModel
            ->join('produtos P', 'P.id = locacoes_produtos.produto_id')
            ->select('locacoes_produtos.*, P.nome, P.numero_serie, P.acessorios ')
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

    public function excluir($id)
    {
        $locacaoModel = new LocacoesModel();

        $dados = [
            'excluido' => 1,
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
        $builder = $locacoesModel->select('locacao.*, clientes.nome as cliente_nome, clientes.razao_social as cliente_razao_social')
            ->join('clientes', 'clientes.id = locacao.cliente_id');

        // Filtrar locações canceladas apenas se a opção foi selecionada
        if (!empty($situacao)) {
            $builder->where('locacao.situacao', $situacao);
        } else {
            $builder->where('locacao.situacao !=', 5); // Excluir canceladas por padrão
        }

        // Filtros de busca por palavra-chave
        if (!empty($palavra)) {
            switch ($tipo) {
                case '1': // Buscar por Data
                    if (strtotime($palavra)) {
                        $builder->where('DATE(locacao.created_at)', date('Y-m-d', strtotime($palavra)));
                    }
                    break;
                case '2': // Buscar por Nome do Cliente
                    $builder->like('clientes.nome', $palavra);
                    break;
                case '3': // Buscar por Razão Social
                    $builder->like('clientes.razao_social', $palavra);
                    break;
                case '4': // Buscar por Código da Locação
                    $builder->where('locacao.id', $palavra);
                    break;
            }
        }

        // Ordenar em ordem crescente pela data de criação
        $builder->orderBy('locacao.created_at', 'DESC');

        $locacoes = $builder->findAll();

        // Formatação de datas e valores
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
        $disponivel = $this->verificarDisponibilidade([$produto_id], $data_entrega, $data_devolucao, $locacao_id = null, $quantidade_solicitada);

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
    public function confirmarlocacao($id)
    {
        $locacaoModel = new LocacoesModel();

        $dados = [
            'situacao' => 4,
        ];
        $locacaoModel->update($id, $dados);

        return redirect()->to('/locacoes')
            ->with('success', 'Locação atualizada com sucesso!');
    }


    public function pagamento($id)
    {
        $locacaoModel = new LocacoesModel();
        $locacao = $locacaoModel->find($id);
        if ($locacao['pagamento'] == 0) {
            $data = [
                'pagamento' => 1
            ];
            // print_r($data);
            // exit;
            $locacaoModel->update($id, $data);
        } else {
            $data = [
                'pagamento' => 0
            ];
            $locacaoModel->update($id, $data);
        }

        return redirect()->to('/locacoes')
            ->with('success', 'Locação atualizada com sucesso!');
    }
}
