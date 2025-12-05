<?php

namespace App\Models;

use CodeIgniter\Model;

class FuncoesModel extends Model
{

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


    function simpleEncrypt($id, $key)
    {
        $intId = (int)$id;
        return rtrim(strtr(base64_encode((string)($intId ^ crc32($key))), '+/', '-_'), '=');
    }

    function simpleDecrypt($hash, $key)
    {
        $decoded = base64_decode(strtr($hash, '-_', '+/'));
        $intDecoded = (int)$decoded; // converte a string para número
        return (int)($intDecoded ^ crc32($key));
    }
}
