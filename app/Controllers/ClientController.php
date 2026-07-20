<?php

namespace App\Controllers;

use App\Models\ClientModel;
use CodeIgniter\Controller;

class ClientController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = service('session');
    }

    public function dashboard()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role_id') != 2) {
            return redirect()->to('/login');
        }

        return view('client/dashboard');
    }

    public function solde()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role_id') != 2) {
            return redirect()->to('/login');
        }

        $userId = $this->session->get('user_id');
        $clientModel = new ClientModel();
        $client = $clientModel->findByUserId($userId);

        $solde = $client ? $client->solde : 0;

        return view('client/solde', ['solde' => $solde]);
    }
}
