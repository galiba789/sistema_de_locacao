<?php

namespace App\Models;

use CodeIgniter\Model;

class WhatsappModel extends Model
{
    protected $table            = 'whatsapp_instance';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
     protected $allowedFields    = ['nome_url', 'nome_instance', 'selecionada', 'status', 'numero', 'id', 'qrcode', 'created_at'];

    public function getAll()
    {
        return $this->findAll();
    }

    public function insertInstance($data)
    {
        return $this->insert($data);
    }

    public function updateByName($nome, $data)
    {
        return $this->where('nome_url', $nome)->set($data)->update();
    }

    public function selectCustom($conditionals = [])
    {
        $builder = $this->db->table($this->table)->select('*');

        foreach ($conditionals as $key => $value) {
            $builder->where($key, $value);
        }

        $builder->orderBy('id', 'DESC');

        return $builder->get()->getResult();
    }

    public function deleteInstance($id)
    {
        return $this->where('id', $id)->delete();
    }


    // ============================================================
    // FUNÇÕES ESPECÍFICAS
    // ============================================================

    public function desselecionarTodas()
    {
        return $this->db->table($this->table)
            ->set('selecionada', 0)
            ->update();
    }

    public function selecionarInstancia($id)
    {
        return $this->db->table($this->table)
            ->where('id', $id)
            ->set('selecionada', 1)
            ->update();
    }


    // ============================================================
    // ENVIAR MENSAGEM - EVOLUTION API
    // ============================================================

    public function enviarMensagem($numero, $mensagem, $resposta = null)
    {
        // Pega instância selecionada
        $instancia = $this->where('selecionada', 1)->first();

        if (!$instancia) {
            log_message('error', 'Nenhuma instância de WhatsApp selecionada.');
            return false;
        }

        $instanciaNome = $instancia['nome_url'];

        // Monta mensagem

        return $this->sendToEvolution($numero, $mensagem, $instanciaNome);
    }

    public function confirmaChamado($numero)
    {
        // Pega instância selecionada
        $instancia = $this->where('selecionada', 1)->first();

        if (!$instancia) {
            log_message('error', 'Nenhuma instância de WhatsApp selecionada.');
            return false;
        }

        $instanciaNome = $instancia['nome_url'];

        $mensagem = "Olá! Seu Contrato foi registrado.\n";

        return $this->sendToEvolution($numero, $mensagem, $instanciaNome);
    }


    // ============================================================
    // FUNÇÃO PRIVADA PARA ENVIAR CURL
    // ============================================================

    private function sendToEvolution($numero, $mensagem, $instancia)
    {
        $apiUrl = EVOLUTION_API_URL;
        $apiKey = API_KEY;

        $payload = [
            'number' => $numero,
            'text'   => $mensagem
        ];

        $ch = curl_init("{$apiUrl}/message/sendText/{$instancia}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "apikey: {$apiKey}"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        $response = curl_exec($ch);
        curl_close($ch);

        $decode = json_decode($response, true);

        if (isset($decode['error'])) {
            log_message('error', 'Erro ao enviar mensagem: ' . $decode['error']);
            return false;
        }

        return true;
    }
}
