<?php

namespace App\Models;

use CodeIgniter\Model;

class Users extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'nome','email', 'password'];

    public function getUserByEmail($email)
    {
        return $this->asArray()->where(['email' => $email])->first();
    }

    function encrypt($plaintext, $key)
    {
        // Define o método de criptografia (AES-256-CBC) e um IV de 16 bytes
        $cipher = "AES-256-CBC";
        $iv = openssl_random_pseudo_bytes(16); // Gera um IV de 16 bytes aleatórios

        // Criptografa o texto
        $encrypted = openssl_encrypt($plaintext, $cipher, $key, 0, $iv);

        // Retorna o texto criptografado com o IV concatenado (codificados em base64)
        return base64_encode($encrypted . '::' . $iv);
    }

    function decrypt($encryptedData, $key)
    {
        // Define o método de criptografia
        $cipher = "AES-256-CBC";

        // Decodifica o texto criptografado e separa o IV do dado criptografado
        list($encrypted, $iv) = explode('::', base64_decode($encryptedData), 2);

        // Descriptografa o texto usando o IV correto (16 bytes)
        return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
    }
}
