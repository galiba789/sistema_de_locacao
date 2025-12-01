<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\ProdutosModel;
use App\Models\LocacoesProdutosModel;

class Api extends ResourceController
{
    protected $format = 'json';

    public function verificar()
    {
        $data = $this->request->getJSON(true); 

        $produtoNome    = $data['produto'] ?? null;
        $data_entrega   = $data['data_inicio'] ?? null;
        $data_devolucao = $data['data_fim'] ?? null;
        $quantidade     = $data['quantidade'] ?? 1;

        // Validação de parâmetros obrigatórios
        if (empty($produtoNome) || empty($data_entrega) || empty($data_devolucao)) {
            return [
                'success' => false,
                'message' => 'Parâmetros obrigatórios ausentes: produto, data_inicio e data_fim.',
                'relacionados' => []
            ];
        }

        $produtosModel = new ProdutosModel();

        // Busca produtos pelo nome
        $produtos = $produtosModel
            ->like('nome', $produtoNome)
            ->findAll();

        if (empty($produtos)) {
            return [
                'success' => false,
                'message' => "Nenhum produto encontrado com o nome '{$produtoNome}'.",
                'relacionados' => []
            ];
        }

        // Prepara arrays para verificação
        $produtosIds = array_column($produtos, 'id');
        $quantidadesArray = array_fill(0, count($produtosIds), $quantidade);

        // Verifica disponibilidade
        $disponibilidade = $this->verificarDisponibilidade($produtosIds, $data_entrega, $data_devolucao, null, $quantidadesArray);

        // Se não disponível
        if (!$disponibilidade['success']) {
            return [
                'success' => false,
                'message' => $disponibilidade['message'],
                'relacionados' => array_map(fn($p) => $p['nome'], $produtos)
            ];
        }

        // Produto disponível
        return [
            'success' => true,
            'message' => 'Produto disponível para as datas informadas!',
            'relacionados' => array_map(fn($p) => $p['nome'], $produtos)
        ];
    }


    private function verificarDisponibilidade($produtos, $data_entrega, $data_devolucao, $locacao_id, $quantidade_solicitada)
    {
        $produtosModel = new ProdutosModel();
        $locacoesProdutoModel = new LocacoesProdutosModel();
        $data_entrega = date('Y-m-d H:i:s', strtotime($data_entrega));
        $data_devolucao = date('Y-m-d H:i:s', strtotime($data_devolucao));

        foreach ($produtos as $index => $produto_id) {
            $produto_info = $produtosModel->where('id', $produto_id)->get()->getRow();

            if (!$produto_info) {
                return [
                    'success' => false,
                    'message' => "Produto com ID '{$produto_id}' não encontrado."
                ];
            }

            $nome_produto = $produto_info->nome;
            $quantidade_estoque = $produto_info->quantidade;

            $query = $locacoesProdutoModel
                ->selectSum('locacoes_produtos.quantidade', 'quantidade_alocada')
                ->join('locacao', 'locacao.id = locacoes_produtos.locacao_id')
                ->where('locacoes_produtos.produto_id', $produto_id)
                ->where('locacao.data_entrega <=', $data_devolucao)
                ->where('locacao.data_devolucao >=', $data_entrega)
                ->whereNotIn('locacao.situacao', [4, 5])
                ->groupBy('locacoes_produtos.produto_id');

            if ($locacao_id) {
                $query->where('locacao.id !=', $locacao_id);
            }

            $result = $query->get()->getRow();
            $quantidade_alocada = $result ? (int)$result->quantidade_alocada : 0;
            $quantidade_requerida = $quantidade_solicitada[$index] ?? 1;
            $quantidade_disponivel = $quantidade_estoque - $quantidade_alocada;

            if ($quantidade_requerida > $quantidade_disponivel) {
                return [
                    'success' => false,
                    'message' => "O produto '{$nome_produto}' não está disponível na quantidade solicitada. Disponível: {$quantidade_disponivel}."
                ];
            }
        }

        return ['success' => true];
    }
}
