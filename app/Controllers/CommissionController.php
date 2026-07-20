<?php

namespace App\Controllers;

use App\Models\CommissionOperateurModel;
use App\Models\OperateurModel;
use CodeIgniter\Controller;

class CommissionController extends BaseController
{
    protected $session;
    protected $commissionModel;
    protected $operateurModel;

    public function __construct()
    {
        $this->session = service('session');
        $this->commissionModel = new CommissionOperateurModel();
        $this->operateurModel = new OperateurModel();
    }

    public function index()
    {
        $destination = $this->request->getGet('destination');

        $operateurs = $this->operateurModel->getAll();
        $source = null;
        foreach ($operateurs as $op) {
            if ($op->code_prefixe === '034') {
                $source = $op;
                break;
            }
        }

        if (!$source) {
            return redirect()->to('/admin/dashboard')->with('error', 'Opérateur source introuvable.');
        }

        $commissions = [];
        $selectedDest = null;

        if ($destination) {
            $selectedDest = $this->operateurModel->find($destination);
            if ($selectedDest) {
                $commissions[$selectedDest->operateur_nom] = $this->commissionModel->getByOperateurs($source->id, $selectedDest->id);
            }
        } else {
            foreach ($operateurs as $dest) {
                if ((int) $dest->id === (int) $source->id) {
                    continue;
                }
                $commissions[$dest->operateur_nom] = $this->commissionModel->getByOperateurs($source->id, $dest->id);
            }
        }

        $data = [
            'commissions' => $commissions,
            'operateurs' => $operateurs,
            'source' => $source,
            'selectedDest' => $selectedDest,
        ];

        return view('commission/index', $data);
    }

    public function create()
    {
        helper(['form', 'url']);
        
        $operateurs = $this->operateurModel->getAll();
        $source = null;
        foreach ($operateurs as $op) {
            if ($op->code_prefixe === '034') {
                $source = $op;
                break;
            }
        }

        if (!$source) {
            return redirect()->to('/commission')->with('error', 'Opérateur source introuvable.');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'id_prefixe_dest' => 'required|numeric|greater_than[0]',
                'montant_min' => 'required|numeric|greater_than[0]',
                'montant_max' => 'required|numeric|greater_than[0]',
                'commission_pct' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]'
            ];

            if (!$this->validate($rules)) {
                return view('commission/create', [
                    'validation' => $this->validator,
                    'operateurs' => $operateurs,
                    'source' => $source
                ]);
            }

            $this->commissionModel->save([
                'id_prefixe_source' => $source->id,
                'id_prefixe_dest' => $this->request->getPost('id_prefixe_dest'),
                'montant_min' => $this->request->getPost('montant_min'),
                'montant_max' => $this->request->getPost('montant_max'),
                'commission_pct' => $this->request->getPost('commission_pct')
            ]);

            return redirect()->to('/commission')->with('success', 'Commission ajoutée avec succès.');
        }

        return view('commission/create', [
            'operateurs' => $operateurs,
            'source' => $source
        ]);
    }

    public function delete($id = null)
    {
        $commission = $this->commissionModel->find($id);
        if (!$commission) {
            return redirect()->to('/commission')->with('error', 'Commission non trouvée.');
        }

        $this->commissionModel->delete($id);
        return redirect()->to('/commission')->with('success', 'Commission supprimée avec succès.');
    }
}
