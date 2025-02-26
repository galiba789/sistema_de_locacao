<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdutosModel extends Model
{
    protected $table            = 'produtos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'nome', 'categoria_id', 'numero_serie', 'sku', 'preco_diaria', 'valor_minimo', 'quantidade', 'obs', 'aditivo_contratual','acessorios', 'status', 'created_at', 'updated_at'];

    public function getAtivos(){
        $query = "SELECT * FROM produtos WHERE status = 1 ORDER BY id DESC";
        $db = db_connect();
        return $db->query($query)->getResult('array');
    }  

    public function getByLocacao($id)
   {
    $query = "SELECT * FROM produtos WHERE id = $id";
    $db = db_connect();
    return $db->query($query)->getResult('array');
   }

}
