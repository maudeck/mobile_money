<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\TypeOperationModel;
use App\Models\TrancheFraisModel;
use App\Models\VueHistoriqueModel;
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

    public function historique()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role_id') != 2) {
            return redirect()->to('/login');
        }

        $userId = $this->session->get('user_id');
        $clientModel = new ClientModel();
        $client = $clientModel->findByUserId($userId);

        $telephone = null;

        if ($client) {
            $user = db_connect()->table('user')->where('id', $client->id_user)->get()->getFirstRow();
            $telephone = $user ? $user->telephone : null;
        }

        $historique = [];

        if ($telephone) {
            $vueModel = new VueHistoriqueModel();
            $historique = $vueModel->getHistoriqueByTelephone($telephone);
        }

        return view('client/historique', ['historique' => $historique]);
    }

    public function operation(string $slug)
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role_id') != 2) {
            return redirect()->to('/login');
        }

        $typeModel = new TypeOperationModel();
        $type = $typeModel->where('LOWER(libelle)', strtolower($slug))->first();

        if (!$type) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = ['type' => $type];

        if (strtolower($type->libelle) === 'transfert') {
            $clientModel = new ClientModel();
            $currentUserId = $this->session->get('user_id');

            $clients = $clientModel->select('user.id, user.telephone')
                ->join('user', 'user.id = client.id_user')
                ->where('client.id_user !=', $currentUserId)
                ->findAll();

            $data['clients'] = $clients;
        }

        return view('client/' . strtolower($type->libelle), $data);
    }

    public function calculFrais(string $typeSlug)
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role_id') != 2) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $montant = $this->request->getPost('montant');

        if (!$montant || !is_numeric($montant)) {
            return $this->response->setJSON(['error' => 'Montant invalide']);
        }

        $typeModel = new TypeOperationModel();
        $type = $typeModel->where('LOWER(libelle)', strtolower($typeSlug))->first();

        if (!$type) {
            return $this->response->setJSON(['error' => 'Type d\'opération introuvable']);
        }

        $frais = 0;
        $commission_pct = 0;

        if (strtolower($type->libelle) === 'transfert') {
            $beneficiaireTel = $this->request->getPost('beneficiaire');
            if (!$beneficiaireTel) {
                return $this->response->setJSON(['error' => 'Bénéficiaire requis']);
            }

            $beneficiaireUser = db_connect()->table('user')->where('telephone', $beneficiaireTel)->get()->getFirstRow();
            if (!$beneficiaireUser) {
                return $this->response->setJSON(['error' => 'Bénéficiaire introuvable']);
            }

            $clientModel = new ClientModel();
            $emetteur = $clientModel->findByUserId($this->session->get('user_id'));
            $beneficiaireClient = $clientModel->findByUserId($beneficiaireUser->id);

            if (!$emetteur || !$beneficiaireClient) {
                return $this->response->setJSON(['error' => 'Client introuvable']);
            }

            $commissionModel = new CommissionOperateurModel();
            $commissionInfo = $commissionModel->findByOperateurs($emetteur->id_prefixe, $beneficiaireClient->id_prefixe);

            if (!$commissionInfo) {
                return $this->response->setJSON(['error' => 'Aucune commission configurée pour cette paire d\'opérateurs']);
            }

            $commission_pct = (float) $commissionInfo->commission_pct;

            $trancheModel = new TrancheFraisModel();
            $tranche = $trancheModel->where('id_type_operation', $type->id)
                ->where('montant_min <=', $montant)
                ->where('montant_max >=', $montant)
                ->first();

            if ($tranche) {
                $frais = (float) $tranche->frais;
            }
        } else {
            $trancheModel = new TrancheFraisModel();
            $tranche = $trancheModel->where('id_type_operation', $type->id)
                ->where('montant_min <=', $montant)
                ->where('montant_max >=', $montant)
                ->first();

            if (!$tranche) {
                $minTranche = $trancheModel->where('id_type_operation', $type->id)
                    ->orderBy('montant_min', 'ASC')
                    ->first();

                if (!$minTranche) {
                    return $this->response->setJSON(['frais' => 0, 'commission_pct' => 0]);
                }

                if ((float) $montant < (float) $minTranche->montant_min) {
                    return $this->response->setJSON(['error' => 'Montant trop petit']);
                }

                return $this->response->setJSON(['error' => 'Montant trop grand']);
            }

            $frais = $tranche->frais;
            $commission_pct = (float) $tranche->commission_pct;
        }

        return $this->response->setJSON([
            'frais' => $frais,
            'commission_pct' => $commission_pct
        ]);
    }

    public function depot()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role_id') != 2) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $montant = $this->request->getPost('montant');
        $fraisApp = $this->request->getPost('frais_applique');

        if (!$montant || !is_numeric($montant) || $fraisApp === null || !is_numeric($fraisApp)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Données invalides']);
        }

        $userId = $this->session->get('user_id');
        $clientModel = new ClientModel();
        $client = $clientModel->findByUserId($userId);

        if (!$client) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Client introuvable']);
        }

        $typeModel = new TypeOperationModel();
        $type = $typeModel->where('LOWER(libelle)', 'depot')->first();

        if (!$type) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Type d\'opération introuvable']);
        }

        db_connect()->transStart();

        db_connect()->table('client')->where('id', $client->id)->update([
            'solde' => $client->solde + (float) $montant,
        ]);

        db_connect()->table('operation')->insert([
            'date_operation'     => date('Y-m-d H:i:s'),
            'montant'            => (float) $montant,
            'frais_applique'     => (float) $fraisApp,
            'id_client_emetteur' => $client->id,
            'id_client_destinataire' => null,
            'id_type_operation'  => $type->id,
        ]);

        db_connect()->transComplete();

        if (db_connect()->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Erreur lors de l\'enregistrement']);
        }

        return $this->response->setJSON(['success' => true, 'nouveau_solde' => $client->solde + (float) $montant]);
    }

    public function retrait()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role_id') != 2) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }

        $montant = $this->request->getPost('montant');
        $fraisApp = $this->request->getPost('frais_applique');

        if (!$montant || !is_numeric($montant) || $fraisApp === null || !is_numeric($fraisApp)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Données invalides']);
        }

        $userId = $this->session->get('user_id');
        $clientModel = new ClientModel();
        $client = $clientModel->findByUserId($userId);

        if (!$client) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Client introuvable']);
        }

        $totalDebit = (float) $montant + (float) $fraisApp;

        if ($client->solde < $totalDebit) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Solde insuffisant. Vous avez besoin de ' . $totalDebit . ' Ar (montant + frais) mais votre solde est de ' . $client->solde . ' Ar.']);
        }

        $typeModel = new TypeOperationModel();
        $type = $typeModel->where('LOWER(libelle)', 'retrait')->first();

        if (!$type) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Type d\'opération introuvable']);
        }

        db_connect()->transStart();

        db_connect()->table('client')->where('id', $client->id)->update([
            'solde' => $client->solde - $totalDebit,
        ]);

        db_connect()->table('operation')->insert([
            'date_operation'     => date('Y-m-d H:i:s'),
            'montant'            => (float) $montant,
            'frais_applique'     => (float) $fraisApp,
            'id_client_emetteur' => $client->id,
            'id_client_destinataire' => null,
            'id_type_operation'  => $type->id,
        ]);

        db_connect()->transComplete();

        if (db_connect()->transStatus() === false) {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Erreur lors de l\'enregistrement']);
        }

        return $this->response->setJSON(['success' => true, 'nouveau_solde' => $client->solde - $totalDebit]);
    }

    public function transfert()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role_id') != 2) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $montant = $this->request->getPost('montant');
        $beneficiaireTel = $this->request->getPost('beneficiaire');

        if (!$montant || !is_numeric($montant) || empty($beneficiaireTel)) {
            return $this->response->setJSON(['error' => 'Données invalides'])->setStatusCode(400);
        }

        $userId = $this->session->get('user_id');
        $clientModel = new ClientModel();

        $emetteur = $clientModel->findByUserId($userId);

        if (!$emetteur) {
            return $this->response->setJSON(['error' => 'Client emetteur introuvable'])->setStatusCode(404);
        }

        $beneficiaireUser = db_connect()->table('user')->where('telephone', $beneficiaireTel)->get()->getFirstRow();
        if (!$beneficiaireUser) {
            return $this->response->setJSON(['error' => 'Beneficiaire introuvable'])->setStatusCode(404);
        }

        $beneficiaireClient = $clientModel->findByUserId($beneficiaireUser->id);
        if (!$beneficiaireClient) {
            return $this->response->setJSON(['error' => 'Beneficiaire introuvable'])->setStatusCode(404);
        }

        $typeModel = new TypeOperationModel();
        $type = $typeModel->where('LOWER(libelle)', 'transfert')->first();

        if (!$type) {
            return $this->response->setJSON(['error' => 'Type d\'opération introuvable'])->setStatusCode(404);
        }

        $commissionModel = new CommissionOperateurModel();
        $commissionInfo = $commissionModel->findByOperateurs($emetteur->id_prefixe, $beneficiaireClient->id_prefixe);

        $commission = 0;
        if ($commissionInfo && (float) $commissionInfo->commission_pct > 0) {
            $commission = (float) $montant * ((float) $commissionInfo->commission_pct / 100);
        }

        $totalDebit = (float) $montant + $commission;

        if ($emetteur->solde < $totalDebit) {
            return $this->response->setJSON(['error' => 'Solde insuffisant'])->setStatusCode(400);
        }

        try {
            db_connect()->transStart();

            db_connect()->table('client')->where('id', $emetteur->id)->update([
                'solde' => $emetteur->solde - $totalDebit,
            ]);

            db_connect()->table('client')->where('id', $beneficiaireClient->id)->update([
                'solde' => $beneficiaireClient->solde + (float) $montant,
            ]);

            db_connect()->table('operation')->insert([
                'date_operation'     => date('Y-m-d H:i:s'),
                'montant'            => (float) $montant,
                'frais_applique'     => $commission,
                'id_client_emetteur' => $emetteur->id,
                'id_client_destinataire' => $beneficiaireClient->id,
                'id_type_operation'  => $type->id,
            ]);

            db_connect()->transComplete();

            if (db_connect()->transStatus() === false) {
                return $this->response->setJSON(['error' => 'Erreur lors de l\'enregistrement'])->setStatusCode(500);
            }

            return $this->response->setJSON(['success' => true, 'nouveau_solde' => $emetteur->solde - $totalDebit]);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->response->setJSON(['error' => 'Erreur serveur: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

    public function transfertMultiple()
    {
        if (!$this->session->get('isLoggedIn') || $this->session->get('role_id') != 2) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        $beneficiaires = $this->request->getPost('beneficiaires');
        $montantTotal = $this->request->getPost('montant_total');
        $fraisApp = $this->request->getPost('frais_applique');

        if (empty($beneficiaires) || !$montantTotal || !is_numeric($montantTotal) || $fraisApp === null || !is_numeric($fraisApp)) {
            return $this->response->setJSON(['error' => 'Données invalides'])->setStatusCode(400);
        }

        $userId = $this->session->get('user_id');
        $clientModel = new ClientModel();
        $emetteur = $clientModel->findByUserId($userId);

        if (!$emetteur) {
            return $this->response->setJSON(['error' => 'Client emetteur introuvable'])->setStatusCode(404);
        }

        foreach ($beneficiaires as $tel) {
            $prefixe = substr($tel, 0, 3);
            if ($prefixe !== '034') {
                return $this->response->setJSON(['error' => 'Le transfert multiple est reserve aux operateurs Telma (034). Telephone invalide : ' . $tel])->setStatusCode(400);
            }
        }

        $typeModel = new TypeOperationModel();
        $type = $typeModel->where('LOWER(libelle)', 'transfert')->first();

        if (!$type) {
            return $this->response->setJSON(['error' => 'Type d\'opération introuvable'])->setStatusCode(404);
        }

        $montantTotal = (float) $montantTotal;
        $fraisApp = (float) $fraisApp;
        $totalDebit = $montantTotal + $fraisApp;
        $nombreBeneficiaires = count($beneficiaires);
        $montantParBeneficiaire = $montantTotal / $nombreBeneficiaires;

        if ($emetteur->solde < $totalDebit) {
            return $this->response->setJSON(['error' => 'Solde insuffisant. Total requis : ' . $totalDebit . ' Ar mais votre solde est de ' . $emetteur->solde . ' Ar.'])->setStatusCode(400);
        }

        try {
            db_connect()->transStart();

            db_connect()->table('client')->where('id', $emetteur->id)->update([
                'solde' => $emetteur->solde - $totalDebit,
            ]);

            foreach ($beneficiaires as $tel) {
                $beneficiaireUser = db_connect()->table('user')->where('telephone', $tel)->get()->getFirstRow();
                if (!$beneficiaireUser) {
                    db_connect()->transRollback();
                    return $this->response->setJSON(['error' => 'Beneficiaire introuvable : ' . $tel])->setStatusCode(404);
                }

                $beneficiaireClient = $clientModel->findByUserId($beneficiaireUser->id);
                if (!$beneficiaireClient) {
                    db_connect()->transRollback();
                    return $this->response->setJSON(['error' => 'Beneficiaire introuvable : ' . $tel])->setStatusCode(404);
                }

                db_connect()->table('client')->where('id', $beneficiaireClient->id)->update([
                    'solde' => $beneficiaireClient->solde + $montantParBeneficiaire,
                ]);

                db_connect()->table('operation')->insert([
                    'date_operation'     => date('Y-m-d H:i:s'),
                    'montant'            => $montantParBeneficiaire,
                    'frais_applique'     => $fraisApp,
                    'id_client_emetteur' => $emetteur->id,
                    'id_client_destinataire' => $beneficiaireClient->id,
                    'id_type_operation'  => $type->id,
                ]);
            }

            db_connect()->transComplete();

            if (db_connect()->transStatus() === false) {
                return $this->response->setJSON(['error' => 'Erreur lors de l\'enregistrement'])->setStatusCode(500);
            }

            return $this->response->setJSON(['success' => true, 'nouveau_solde' => $emetteur->solde - $totalDebit, 'count' => $nombreBeneficiaires]);
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return $this->response->setJSON(['error' => 'Erreur serveur: ' . $e->getMessage()])->setStatusCode(500);
        }
    }
}
