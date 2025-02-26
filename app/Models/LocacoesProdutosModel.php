<?php

namespace App\Models;

use CodeIgniter\Model;

class LocacoesProdutosModel extends Model
{
    protected $table            = 'locacoes_produtos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'locacao_id', 'produto_id', 'quantidade', 'valor_unitario', 'subtotal', 'data_retirada', 'data_devolucao_real', 'created_at', 'updated_at'];

   public function getByLocacaoId($id)
   {
        $query = "SELECT * FROM locacoes_produtos WHERE locacao_id = $id";
        $db = db_connect();
        return $db->query($query)->getResult('array');
   }

}
