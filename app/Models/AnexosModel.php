<?php

namespace App\Models;

use CodeIgniter\Model;

class AnexosModel extends Model
{
    protected $table            = 'anexos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'locacao_id', 'anexo', 'created_at'];

}
