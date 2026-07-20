<?php

namespace App\Controllers;

use App\Models\UserModel;
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
            $this->userModel->createClient($telephone);
            $user = $this->userModel->findByTelephone($telephone);
        }

        $this->session->set([
            'user_id'    => $user->id,
            'telephone'  => $user->telephone,
            'role'       => $user->role,
            'isLoggedIn' => true
        ]);

        return $this->redirectToRole();
    }

    public function adminDashboard()
    {
        $this->requireRole('admin');
        return view('admin/dashboard');
    }

    public function clientDashboard()
    {
        $this->requireRole('client');
        return view('client/dashboard');
    }

    private function requireRole(string $role)
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role') !== $role) {
            return redirect()->to('/login');
        }
    }

    private function redirectToRole()
    {
        $role = $this->session->get('role');

        if ($role === 'admin') {
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
