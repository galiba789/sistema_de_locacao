<?php

namespace App\Models;

use CodeIgniter\Model;

class CondicaoPagamentoModel extends Model
{
    protected $table            = 'condicaoPagamento';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id','nome', 'ativo', 'excluido'];

 
}
