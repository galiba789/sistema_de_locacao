<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Users as ModelsUsers;
use CodeIgniter\HTTP\ResponseInterface;

class Users extends BaseController
{
    public function index()
    {
        $userModel = new ModelsUsers();

        $pagina = $this->request->getVar('page') ?? 1;

        // Define o número de itens por página
        $itensPorPagina = 10;

        // Busca os dados paginados
        $usuarios = $userModel
            ->orderBy('users.id', 'DESC')
            ->paginate($itensPorPagina);

        // Gera os links de paginação automaticamente
        $paginacao = $userModel->pager;
        $data = [
            'usuarios' => $usuarios,
            'paginacao' => $paginacao,
        ];
        


        return view('dashboard/cadastros/usuarios/index', $data);
    }

    public function cadastrar(){

        return view('dashboard/cadastros/usuarios/cadastro');
    }

    public function salvar(){
        $senha = $this->request->getPost('password');
        $nome = $this->request->getPost('nome');
        $email = $this->request->getPost('email');
        $userModel = new ModelsUsers();

        $data = [
            'nome' => $nome,
            'email' => $email,
            'password' => $userModel->encrypt($senha, 1),
        ];

        $userModel->insert($data);
        return redirect()->to('usuarios')
            ->with('success', 'Usuario cadastrado com sucesso');
    }

    public function edita($id){
        $userModel = new ModelsUsers();
        $user = $userModel->find($id);
      
        
        $data = [
            'usuario' => $user,
            'password' => $userModel->decrypt($user['password'], 1),
        ];

        return view('dashboard/cadastros/usuarios/editar', $data);
    }

    public function editar($id){
        $userModel = new ModelsUsers();
        $user = $userModel->find($id);
        $senha = $this->request->getPost('password');
        $nome = $this->request->getPost('nome');
        $email = $this->request->getPost('email');
        
        $data = [
            'nome' => $nome,
            'email' => $email,
            'password' => $userModel->encrypt($senha, 1),
        ];

        $userModel->update($id ,$data);
        return redirect()->to('usuarios')
            ->with('success', 'Usuario cadastrado com sucesso');
    }

    public function excluir($id){
        $userModel = new ModelsUsers();
        if ($userModel->delete($id)) {
            return redirect()->to('/usuarios')->with('success', 'Orçamento excluído com sucesso.');
        }
        return redirect()->back()->with('error', 'Erro ao excluir o orçamento.');
    }
}
