<?php

namespace App\Controllers;

use App\Models\OperateurModel;
use CodeIgniter\Controller;

class OperateurController extends BaseController
{
    protected $session;
    protected $operateurModel;

    public function __construct()
    {
        $this->session = service('session');
        $this->operateurModel = new OperateurModel();
    }

    public function index()
    {
        $data['operateurs'] = $this->operateurModel->getAll();
        return view('operateur/index', $data);
    }

    public function create()
    {
        helper(['form', 'url']);
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'code_prefixe' => 'required|min_length[3]|max_length[4]|is_unique[prefixe_operateur.code_prefixe]',
                'operateur_nom' => 'required|min_length[2]|max_length[50]'
            ];

            if (!$this->validate($rules)) {
                return view('operateur/create', [
                    'validation' => $this->validator
                ]);
            }

            $this->operateurModel->save([
                'code_prefixe' => $this->request->getPost('code_prefixe'),
                'operateur_nom' => $this->request->getPost('operateur_nom')
            ]);

            return redirect()->to('/operateur')->with('success', 'Opérateur ajouté avec succès.');
        }

        return view('operateur/create');
    }

    public function edit($id = null)
    {
        helper(['form', 'url']);
        
        $operateur = $this->operateurModel->find($id);
        if (!$operateur) {
            return redirect()->to('/operateur')->with('error', 'Opérateur non trouvé.');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'code_prefixe' => "required|min_length[3]|max_length[4]|is_unique[prefixe_operateur.code_prefixe,id,$id]",
                'operateur_nom' => 'required|min_length[2]|max_length[50]'
            ];

            if (!$this->validate($rules)) {
                return view('operateur/edit', [
                    'validation' => $this->validator,
                    'operateur' => $operateur
                ]);
            }

            $this->operateurModel->update($id, [
                'code_prefixe' => $this->request->getPost('code_prefixe'),
                'operateur_nom' => $this->request->getPost('operateur_nom')
            ]);

            return redirect()->to('/operateur')->with('success', 'Opérateur modifié avec succès.');
        }

        return view('operateur/edit', ['operateur' => $operateur]);
    }

    public function delete($id = null)
    {
        $operateur = $this->operateurModel->find($id);
        if (!$operateur) {
            return redirect()->to('/operateur')->with('error', 'Opérateur non trouvé.');
        }

        $this->operateurModel->delete($id);
        return redirect()->to('/operateur')->with('success', 'Opérateur supprimé avec succès.');
    }
}
