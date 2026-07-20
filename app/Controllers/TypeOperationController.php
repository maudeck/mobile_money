<?php

namespace App\Controllers;

use App\Models\TypeOperationModel;
use App\Models\TrancheFraisModel;
use CodeIgniter\Controller;

class TypeOperationController extends BaseController
{
    protected $session;
    protected $typeOperationModel;
    protected $trancheFraisModel;

    public function __construct()
    {
        $this->session = service('session');
        $this->typeOperationModel = new TypeOperationModel();
        $this->trancheFraisModel = new TrancheFraisModel();
    }

    public function index()
    {
        $data['types'] = $this->typeOperationModel->getAll();
        return view('type_operation/index', $data);
    }

    public function create()
    {
        helper(['form', 'url']);
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'libelle' => 'required|min_length[2]|max_length[50]|is_unique[type_operation.libelle]'
            ];

            if (!$this->validate($rules)) {
                return view('type_operation/create', [
                    'validation' => $this->validator
                ]);
            }

            $this->typeOperationModel->save([
                'libelle' => $this->request->getPost('libelle')
            ]);

            return redirect()->to('/type-operation')->with('success', 'Type d\'opération ajouté avec succès.');
        }

        return view('type_operation/create');
    }

    public function edit($id = null)
    {
        helper(['form', 'url']);
        
        $type = $this->typeOperationModel->find($id);
        if (!$type) {
            return redirect()->to('/type-operation')->with('error', 'Type d\'opération non trouvé.');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'libelle' => "required|min_length[2]|max_length[50]|is_unique[type_operation.libelle,id,$id]"
            ];

            if (!$this->validate($rules)) {
                return view('type_operation/edit', [
                    'validation' => $this->validator,
                    'type' => $type
                ]);
            }

            $this->typeOperationModel->update($id, [
                'libelle' => $this->request->getPost('libelle')
            ]);

            return redirect()->to('/type-operation')->with('success', 'Type d\'opération modifié avec succès.');
        }

        return view('type_operation/edit', ['type' => $type]);
    }

    public function delete($id = null)
    {
        $type = $this->typeOperationModel->find($id);
        if (!$type) {
            return redirect()->to('/type-operation')->with('error', 'Type d\'opération non trouvé.');
        }

        $this->typeOperationModel->delete($id);
        return redirect()->to('/type-operation')->with('success', 'Type d\'opération supprimé avec succès.');
    }

    public function tranches($id = null)
    {
        $type = $this->typeOperationModel->find($id);
        if (!$type) {
            return redirect()->to('/type-operation')->with('error', 'Type d\'opération non trouvé.');
        }

        $data['type'] = $type;
        $data['tranches'] = $this->trancheFraisModel->getByTypeOperation($id);
        return view('type_operation/tranches', $data);
    }

    public function addTranche($id = null)
    {
        $type = $this->typeOperationModel->find($id);
        if (!$type) {
            return redirect()->to('/type-operation')->with('error', 'Type d\'opération non trouvé.');
        }

        helper(['form', 'url']);
        
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'montant_min' => 'required|numeric|greater_than[0]',
                'montant_max' => 'required|numeric|greater_than[0]',
                'frais' => 'required|numeric|greater_than_equal_to[0]'
            ];

            if (!$this->validate($rules)) {
                return view('type_operation/add_tranche', [
                    'validation' => $this->validator,
                    'type' => $type
                ]);
            }

            $this->trancheFraisModel->save([
                'montant_min' => $this->request->getPost('montant_min'),
                'montant_max' => $this->request->getPost('montant_max'),
                'frais' => $this->request->getPost('frais'),
                'id_type_operation' => $id
            ]);

            return redirect()->to('/type-operation/tranches/' . $id)->with('success', 'Tranche ajoutée avec succès.');
        }

        return view('type_operation/add_tranche', ['type' => $type]);
    }

    public function deleteTranche($idType = null, $idTranche = null)
    {
        $tranche = $this->trancheFraisModel->find($idTranche);
        if (!$tranche) {
            return redirect()->to('/type-operation/tranches/' . $idType)->with('error', 'Tranche non trouvée.');
        }

        $this->trancheFraisModel->delete($idTranche);
        return redirect()->to('/type-operation/tranches/' . $idType)->with('success', 'Tranche supprimée avec succès.');
    }
}
