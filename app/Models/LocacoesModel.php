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
        $ultimoDia = date('Y-m-t 23:59:59', strtotime($primeiroDia)); // Incluir até o final do dia

        return $this->where('situacao !=', 5)
            ->where('excluido !=', 1)
            ->where('data_entrega >=', $primeiroDia)
            ->where('data_entrega <=', $ultimoDia)
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



    public function getCategoriasRelatorio(string $dataInicio, string $dataFim, ?string $status = null)
    {
        $builder = $this->db->table('locacoes_produtos lp');

        $builder->select("
        c.id,
        c.nome AS categoria,
        COUNT(DISTINCT lp.locacao_id) AS total_locacoes,
        SUM(lp.quantidade * l.total_diarias) AS total_diarias,
        SUM(lp.quantidade * lp.preco_diaria * l.total_diarias) AS faturamento_total
    ", false);

        $builder->join('locacao l', 'l.id = lp.locacao_id');
        $builder->join('produtos p', 'p.id = lp.produto_id');
        $builder->join('categoria c', 'c.id = p.categoria_id', 'left');

        // Exclui locações canceladas
        $builder->where('l.situacao !=', 5);

        // Filtra pelo mês da entrega
        $builder->where('DATE(l.data_entrega) >=', $dataInicio);
        $builder->where('DATE(l.data_entrega) <=', $dataFim);

        // Se quiser filtrar por status específico
        if ($status !== null) {
            $builder->where('l.situacao', $status);
        }

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

        // Exclui canceladas
        $builder->where('l.situacao !=', 5);

        // Filtro pelo mês da entrega
        $builder->where('DATE(l.data_entrega) >=', $dataInicio);
        $builder->where('DATE(l.data_entrega) <=', $dataFim);

        // Filtra status de pagamento, se definido
        if (!is_null($status) && ($status === '0' || $status === '1')) {
            $builder->where('l.pagamento', $status);
        }

        $builder->orderBy('l.data_entrega', 'DESC');

        return $builder->get()->getResult();
    }


    public function getFaturamentoProximasEntregasHome($dias)
    {
        $hoje = date('Y-m-d');
        $limite = date('Y-m-d', strtotime("+{$dias} days"));

        return $this->select("
        DATE_FORMAT(locacao.data_entrega, '%m') as mes, 
        DATE_FORMAT(locacao.data_entrega, '%M') as nome_mes, 
        SUM(locacao.valor_total) as total
    ")
            ->join('clientes', 'clientes.id = locacao.cliente_id')
            ->where('locacao.data_entrega <=', $limite)
            ->where('locacao.data_entrega >=', $hoje)
            ->where('locacao.situacao !=', 5) // Status diferente de 5
            ->where('locacao.status', 1) // Status ativo
            ->groupBy('mes')
            ->groupBy('nome_mes')
            ->orderBy('mes', 'ASC')
            ->findAll();
    }
    public function getProdutosRelatorio(string $dataInicio, string $dataFim)
    {
        $builder = $this->db->table('locacoes_produtos lp');

        $builder->select("
        p.id,
        p.nome AS produto,
        COUNT(DISTINCT lp.locacao_id) AS total_locacoes,
        SUM(lp.quantidade * l.total_diarias) AS total_diarias,
        SUM(lp.quantidade * lp.preco_diaria * l.total_diarias) AS faturamento_real,
        (SUM(lp.quantidade * lp.preco_diaria * l.total_diarias) / NULLIF(SUM(lp.quantidade * l.total_diarias), 0)) AS valor_medio_diaria
    ", false);

        $builder->join('locacao l', 'l.id = lp.locacao_id');
        $builder->join('produtos p', 'p.id = lp.produto_id');

        $builder->where('l.situacao !=', 5); // ignora canceladas
        $builder->where('DATE(l.data_entrega) >=', $dataInicio);
        $builder->where('DATE(l.data_entrega) <=', $dataFim);

        $builder->groupBy('p.id')
            ->orderBy('faturamento_real', 'DESC');

        return $builder->get()->getResult();
    }

    // Em app/Models/LocacoesModel.php
    public function getFaturamentoMensal(string $dataInicio, string $dataFim, ?string $status = null)
    {
        $builder = $this->db->table('locacao l');

        $builder->select("
        DATE_FORMAT(l.data_entrega, '%Y-%m') AS ano_mes,
        DATE_FORMAT(l.data_entrega, '%m') AS mes,
        DATE_FORMAT(l.data_entrega, '%Y') AS ano,
        SUM(l.valor_total) AS total
    ", false);

        // Somente locações não canceladas
        $builder->where('l.situacao !=', 5);

        // Filtro pelo período de entrega
        $builder->where('DATE(l.data_entrega) >=', $dataInicio);
        $builder->where('DATE(l.data_entrega) <=', $dataFim);

        // Filtra por status de pagamento, se definido
        if (!is_null($status) && ($status === '0' || $status === '1' || is_numeric($status))) {
            $builder->where('l.pagamento', $status);
        }

        // Agrupa por mês da entrega
        $builder->groupBy('ano_mes');
        $builder->orderBy('ano_mes', 'ASC');

        return $builder->get()->getResult();
    }
}
