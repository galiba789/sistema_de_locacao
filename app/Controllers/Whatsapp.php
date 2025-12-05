<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FuncoesModel;
use App\Models\WhatsappModel;
use CodeIgniter\HTTP\ResponseInterface;

class Whatsapp extends BaseController
{
    private $whatsapp;
    private $apiUrl;
    private $apiToken;

    public function __construct()
    {
        $this->whatsapp = new WhatsappModel();

        $this->apiUrl   = EVOLUTION_API_URL;
        $this->apiToken = API_KEY;
    }
    public function index()
    {
        $instances = $this->whatsapp->getAll();

        $data = [
            'instances' => $instances
        ];
        return view('dashboard/cadastros/whatsapp/index', $data);
    }


    // =======================================================
    // CRIAR INSTÂNCIA
    // =======================================================

    public function criar()
    {
        $funcoes = new FuncoesModel();
        $nome = $this->request->getPost('nome_instance');
        $numero = $this->request->getPost('numero');
        // print_r($numero);
        // exit;
        if (!$nome || !$numero) {
            session()->setFlashdata('erro', 'Preencha todos os campos.');
            return redirect()->to('/painel/whatsapp');
        }

        $nome_url = $this->formatar_nome($nome);

        $payload = [
            "instanceName" => $nome_url,
            "number"       => $numero,
            "integration"  => "WHATSAPP-BAILEYS",
            "qrcode"       => true,
            "token"        => $funcoes->encrypt($this->apiToken, ENCRYPTION_KEY)
        ];

        $response = $this->sendCurl("{$this->apiUrl}/instance/create", $payload);

        if (isset($response['instance']['instanceName'])) {

            $this->whatsapp->insert([
                'nome_instance' => $nome,
                'nome_url'       => $response['instance']['instanceName'],
                'numero'         => $numero,
                'status'         => 'DISCONNECTED',
                'created_at'     => date('Y/m/d H:i:s'),
            ]);

            session()->setFlashdata('sucesso', 'Instância criada com sucesso!');
        } else {
            session()->setFlashdata('erro', 'Erro ao criar instância.');
        }

        return redirect()->to('/whatsapp');
    }


    // =======================================================
    // CONECTAR INSTÂNCIA (GERAR QR CODE)
    // =======================================================

    public function conectar($nome)
    {
        $url = "{$this->apiUrl}/instance/connect/$nome";

        $data = $this->sendCurl($url);

        if (isset($data['base64'])) {

            $this->whatsapp->updateByName($nome, [
                'qrcode' => $data['base64'],
                'status' => 'QRCODE'
            ]);

            return view('dashboard/cadastros/whatsapp/conectar', [
                'nome'   => $nome,
                'qrcode' => $data['base64']
            ]);
        }

        session()->setFlashdata('erro', 'Erro ao gerar QR Code.');
        return redirect()->to('/whatsapp');
    }


    // =======================================================
    // CHECK STATUS
    // =======================================================

    public function check_status($nome)
    {
        $data = $this->sendCurl("{$this->apiUrl}/instance/connect/$nome");

        if (isset($data['instance']['state']) && $data['instance']['state'] === 'open') {

            $this->whatsapp->updateByName($nome, ['status' => 'open']);

            return $this->response->setJSON(['status' => 'CONNECTED']);
        }

        return $this->response->setJSON(['status' => 'DISCONNECTED']);
    }


    // =======================================================
    // DESCONECTAR INSTÂNCIA
    // =======================================================

    public function desconectar($nome)
    {
        $data = $this->sendCurl("{$this->apiUrl}/instance/logout/$nome", null, "DELETE");

        if (isset($data['status']) && $data['status'] === 'SUCCESS') {

            $this->whatsapp->updateByName($nome, [
                'status' => 'DISCONNECTED',
                'qrcode' => null
            ]);

            session()->setFlashdata('success', 'Conta desconectada com sucesso.');
        } else {
            session()->setFlashdata('erro', 'Erro ao desconectar a conta.');
        }

        return redirect()->to('/whatsapp');
    }


    // =======================================================
    // EXCLUIR INSTÂNCIA
    // =======================================================

    public function excluir($id)
    {
        $instance = $this->whatsapp->find($id);

        if (!$instance) {
            session()->setFlashdata('erro', 'Instância não encontrada.');
            return redirect()->to('/whatsapp');
        }

        $nome = $instance['nome_url'];

        $data = $this->sendCurl("{$this->apiUrl}/instance/delete/$nome", null, "DELETE");

        if (isset($data['status']) && $data['status'] === 'SUCCESS') {

            $this->whatsapp->delete($id);

            session()->setFlashdata('sucesso', 'Instância excluída com sucesso.');
        } else {
            session()->setFlashdata('erro', 'Erro ao excluir a instância.');
        }

        return redirect()->to('/whatsapp');
    }


    // =======================================================
    // SETAR INSTÂNCIA SELECIONADA
    // =======================================================

    public function set_instancia()
    {
        $nome = $this->request->getPost('instance');
        // print_r($nome);
        // exit;
        if ($nome) {
            $this->whatsapp->desselecionarTodas();
            $this->whatsapp->selecionarInstancia($nome);

            session()->setFlashdata('sucesso', "Instância '{$nome}' selecionada com sucesso!");
        } else {
            session()->setFlashdata('erro', 'Selecione uma instância válida.');
        }

        return redirect()->to('/whatsapp');
    }


    // =======================================================
    // FORMATAR NOME (mesmo do CI3)
    // =======================================================

    private function formatar_nome($nome)
    {
        $nome_sem_acentos = iconv('UTF-8', 'ASCII//TRANSLIT', $nome);
        $nome_sem_acentos = strtolower($nome_sem_acentos);
        $nome_formatado = preg_replace('/[^a-z0-9]+/', '_', $nome_sem_acentos);
        return trim($nome_formatado, '_');
    }


    // =======================================================
    // FUNÇÃO PADRÃO PARA CURL
    // =======================================================

    private function sendCurl($url, $payload = null, $method = "POST")
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($method !== "POST") {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "apikey: {$this->apiToken}"
        ]);

        if ($payload !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
    
    public function teste(){
        $mensagem = "oi";

        $this->whatsapp->enviarMensagem("+55389192-0343", $mensagem);
        return redirect()->to('/whatsapp');
    }
}
