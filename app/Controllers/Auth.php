<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Users;
use CodeIgniter\HTTP\ResponseInterface;

class Auth extends BaseController
{
    public function index()
    {
        return view('index');
    }

    public function login()
    {
        
        $usersModel = new Users();
        $password = $this->request->getPost('password');
        $email = $this->request->getPost('email');
       
        if (empty($email)) {
            return redirect()->back()->with('error', 'Por favor digite um email');
        }

        if (empty($password)) {
            return redirect()->back()->with('error', 'Por favor digite uma senha');
        }

        $user = $usersModel->getUserByEmail($email);
        $corretPassword = $usersModel->decrypt($user['password'], 1);

        if($user && $password === $corretPassword ){
            session()->set([
                'user_id' => $user['id'],
                'name'  => $user['nome'],
                'logged_in' => true,
             
            ]);
            return redirect()->to('/home');

        } 
    }

    public function logout()
    {
        session()->destroy();
        return $this->index();
    }
}
