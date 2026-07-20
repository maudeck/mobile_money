<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ClientModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    protected $session;
    protected $userModel;

    public function __construct()
    {
        $this->session = service('session');
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if ($this->session->get('isLoggedIn')) {
            return $this->redirectToRole();
        }

        helper(['form', 'url']);
        return view('login');
    }

    public function auth()
    {
        $telephone = trim($this->request->getPost('telephone'));

        if (empty($telephone)) {
            return redirect()->to('/login')->with('error', 'Veuillez entrer un numéro de téléphone.');
        }

        $user = $this->userModel->findByTelephone($telephone);

        if (!$user) {
            $prefixe = substr($telephone, 0, 3);
            $prefixeModel = new \App\Models\PrefixeOperateurModel();
            $prefixeInfo = $prefixeModel->where('code_prefixe', $prefixe)->first();

            if (!$prefixeInfo) {
                return redirect()->to('/login')->with('error', 'Numéro de téléphone invalide : préfixe inconnu.');
            }

            $this->userModel->createClient($telephone);
            $user = $this->userModel->findByTelephone($telephone);

            db_connect()->table('client')->insert([
                'id_user'       => $user->id,
                'solde'         => 0,
                'date_creation' => date('Y-m-d'),
                'id_prefixe'    => $prefixeInfo->id,
            ]);
        }

        $this->session->set([
            'user_id'    => $user->id,
            'telephone'  => $user->telephone,
            'role_id'    => $user->role_id,
            'isLoggedIn' => true
        ]);

        return $this->redirectToRole();
    }

    public function adminDashboard()
    {
        $this->requireRole(1);
        return view('admin/dashboard');
    }

    public function clientDashboard()
    {
        $this->requireRole(2);
        return view('client/dashboard');
    }

    private function requireRole(int $roleId)
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role_id') != $roleId) {
            return redirect()->to('/login');
        }
    }

    private function redirectToRole()
    {
        $role_id = $this->session->get('role_id');

        if ($role_id == 1) {
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->to('/client/dashboard');
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/login');
    }
}
