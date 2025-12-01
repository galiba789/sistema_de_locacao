<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AnexosModel;
use App\Models\LocacoesModel;
use CodeIgniter\HTTP\ResponseInterface;

class Pagamentos extends BaseController
{
    public function index($id_locacao)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $locacoesModel = new LocacoesModel();
        $anexoModel = new AnexosModel();

        $locacao = $locacoesModel->find($id_locacao);
        $anexos = $anexoModel->where('locacao_id', $id_locacao)->findAll();
        
        $data = [
            'locacao' => $locacao,
            'anexos' => $anexos
        ];

        return view('dashboard/locacoes/Anexos/index', $data);
    }
    
    public function salvar($id_locacao)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $anexosModel   = new AnexosModel();
        $locacoesModel = new LocacoesModel();

        // Verifica se a locação existe
        $locacao = $locacoesModel->find($id_locacao);
        if (!$locacao) {
            return redirect()->back()->with('error', 'Locação não encontrada.');
        }
        
        // Arquivo enviado
        $file = $this->request->getFile('comprovante');

        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'Nenhum arquivo válido foi enviado.');
        }

        // Gera nome seguro
        $newName = $file->getRandomName();

        // Move o arquivo para a pasta correta
        $file->move('public/uploads/comprovantes/', $newName);

        // Salva no banco
        $anexosModel->insert([
            'locacao_id' => $id_locacao,
            'anexo'      => $newName,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $data = [
            'situacao' => 4,
            'pagamento' => 1, 
        ];

        $locacoesModel->update($id_locacao, $data);

        return redirect()->back()->with('success', 'Comprovante enviado com sucesso!');
    }
}
