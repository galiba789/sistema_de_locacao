<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutosOrcamentoModel extends Model
{
    protected $table            = 'orcamento_produtos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['orcamento_id', 'produto_id', 'quantidade', 'valor_unitario', 'subtotal', 'data_retirada', 'data_devolucao_real', 'created_at', 'updated_at'];

    public function getAtivos(){
        $query = "SELECT * FROM orcamento_produtos WHERE status = 1 ORDER BY id DESC";
        $db = db_connect();
        return $db->query($query)->getResult('array');
    }  

    public function getByLocacao($id)
   {
    $query = "SELECT * FROM orcamento_produtos WHERE orcamento_id = $id";
    $db = db_connect();
    return $db->query($query)->getResult('array');
   }
}
