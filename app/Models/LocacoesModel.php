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
    protected $allowedFields    = ['id', 'descricao', 'cliente_id', 'valor_total', 'situacao', 'status', 'data_entrega', 'data_devolucao', 'total_diarias', 'desconto', 'forma_pagamento', 'observacao', 'acessorios', 'created_at', 'updated_at'];

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
            ->where('locacao.status', 1)
            ->findAll();
    }
}
