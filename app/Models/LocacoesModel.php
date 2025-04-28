<?php

namespace App\Models;

use CodeIgniter\Model;

class LocacoesModel extends Model
{
    protected $table            = 'locacao';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'descricao', 'cliente_id', 'valor_total', 'situacao', 'status', 'data_entrega', 'data_devolucao', 'total_diarias', 'desconto', 'forma_pagamento', 'pagamento', 'observacao', 'acessorios', 'created_at', 'situacao', 'updated_at'];

    public function getAtivos()
    {
        $query = "SELECT * FROM locacao WHERE situacao != 5 ORDER BY id DESC";
        $db = db_connect();
        return $db->query($query)->getResult('array');
    }

    public function getAtivosPorMes($mes, $ano)
    {
        $primeiroDia = "$ano-$mes-01";
        $ultimoDia = date('Y-m-t', strtotime($primeiroDia));

        return $this->where('situacao !=', 5)
            ->where('situacao !=', 4)
            ->where('excluido !=', 1)
            ->groupStart()
            ->where('data_entrega <=', $ultimoDia)
            ->where('data_devolucao >=', $primeiroDia)
            ->groupEnd()
            ->findAll();
    }

    // No seu model de Locações
    public function getLocacoesProximasDeEntrega($dias)
    {
        $hoje = date('Y-m-d');
        $limite = date('Y-m-d', strtotime("+{$dias} days"));

        return $this->select("
        locacao.*,
        CASE 
            WHEN clientes.tipo = 1 THEN clientes.nome
            ELSE clientes.razao_social
        END AS cliente
    ", false)
            ->join('clientes', 'clientes.id = locacao.cliente_id')
            ->where('locacao.data_entrega <=', $limite)
            ->where('locacao.data_entrega >=', $hoje)
            ->where('locacao.situacao !=', 5)
            ->where('locacao.status', 1)
            ->findAll();
    }


    public function getProdutosRelatorio(string $dataInicio, string $dataFim)
    {
        $builder = $this->db->table('locacoes_produtos lp');
        $builder->select("
            p.id,
            p.nome AS produto,
            p.preco_diaria,
            COUNT(DISTINCT lp.locacao_id) AS total_locacoes,
            SUM(lp.quantidade * (DATEDIFF(l.data_devolucao, l.data_entrega) + 1)) AS total_diarias,
            SUM(
                (
                    (lp.quantidade * (DATEDIFF(l.data_devolucao, l.data_entrega) + 1) * p.preco_diaria)
                    /
                    (
                        SELECT SUM(lp2.quantidade * (DATEDIFF(l.data_devolucao, l.data_entrega) + 1) * p2.preco_diaria)
                        FROM locacoes_produtos lp2
                        JOIN produtos p2 ON p2.id = lp2.produto_id
                        WHERE lp2.locacao_id = lp.locacao_id
                    )
                ) * l.valor_total
            ) AS faturamento_real
        ", false);
        $builder->join('locacao l', 'l.id = lp.locacao_id');
        $builder->join('produtos p', 'p.id = lp.produto_id');
        $builder->where('l.situacao !=', 5);
        $builder->where('DATE(l.created_at) >=', $dataInicio);
        $builder->where('DATE(l.created_at) <=', $dataFim);
        $builder->groupBy('p.id')
                ->orderBy('faturamento_real', 'DESC');
    
        return $builder->get()->getResult();
    }
    
    public function getCategoriasRelatorio(string $dataInicio, string $dataFim, ?string $status = null)
    {
        $builder = $this->db->table('locacoes_produtos lp');
        $builder->select("
            c.id,
            c.nome AS categoria,
            COUNT(DISTINCT lp.locacao_id)                                  AS total_locacoes,
            SUM(lp.quantidade * (DATEDIFF(l.data_devolucao, l.data_entrega) + 1)) AS total_diarias,
            SUM(
                (
                    (lp.quantidade * (DATEDIFF(l.data_devolucao, l.data_entrega) + 1) * p.preco_diaria)
                    /
                    (
                        SELECT SUM(lp2.quantidade * (DATEDIFF(l.data_devolucao, l.data_entrega) + 1) * p2.preco_diaria)
                        FROM locacoes_produtos lp2
                        JOIN produtos p2 ON p2.id = lp2.produto_id
                        WHERE lp2.locacao_id = lp.locacao_id
                    )
                ) * l.valor_total
            ) AS faturamento_total
        ", false);
        $builder->join('locacao l',      'l.id          = lp.locacao_id');
        $builder->join('produtos p',     'p.id          = lp.produto_id');
        $builder->join('categoria  c',   'c.id          = p.categoria_id', 'left');
        $builder->where('l.situacao !=', 5);
        $builder->where('DATE(l.created_at) >=', $dataInicio);
        $builder->where('DATE(l.created_at) <=', $dataFim);
        $builder->groupBy('c.id')
                ->orderBy('faturamento_total', 'DESC');
    
        return $builder->get()->getResult();
    }
    

    public function getLocacoesPorPeriodo($dataInicio, $dataFim, $status = null)
    {
        $builder = $this->db->table('locacao l');
        $builder->select("
            l.*,
            CASE 
                WHEN c.tipo = 1 THEN c.nome
                ELSE c.razao_social
            END AS cliente_nome,
            c.tipo AS tipo_cliente
        ", false); 
        $builder->join('clientes c', 'c.id = l.cliente_id');
        $builder->where('l.situacao !=', 5);
        $builder->where('DATE(l.created_at) >=', $dataInicio);
        $builder->where('DATE(l.created_at) <=', $dataFim);
        $builder->orderBy('l.id', 'DESC');
    
        if (!is_null($status) && ($status === '0' || $status === '1')) {
            $builder->where('l.pagamento', $status);
        }
    
        return $builder->get()->getResult();
    }    
    
}
