<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table            = 'categoria';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'nome', 'status'];
    
    public function getAtivos(){
        $query = "SELECT * FROM categoria WHERE status = 1 ORDER BY id DESC";
        $db = db_connect();
        return $db->query($query)->getResult('array');
    }

    public function getById($id)
    {
        return $this->asArray()->where(['id' => $id])->first();
    }
}
