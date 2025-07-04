<?php

namespace App\Models;

use CodeIgniter\Model;

class Clientes extends Model
{
    protected $table            = 'clientes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'tipo', 'nome', 'razao_social', 'cpf', 'rg', 'nascimento', 'cnpj', 'cep', 'rua', 'numero', 'complemento', 'bairro', 'cidade', 'estado', 'telefone_comercial', 'email', 'telefone_contato','observacao', 'email_contato', 'cargo', 'status', 'created_at', 'updated_at', 'localidade', 'logradouro'];


    public function getAtivos(){
        $query = "SELECT * FROM clientes WHERE status = 1 ORDER BY id DESC";
        $db = db_connect();
        return $db->query($query)->getResult('array');
    }

}
