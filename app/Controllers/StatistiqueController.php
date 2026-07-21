<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class StatistiqueController extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = service('session');
    }

    public function gains()
    {
        $db = \Config\Database::connect();

        $typeOperation = $this->request->getGet('type_operation') ?? '';
        $operateurSource = $this->request->getGet('operateur_source') ?? '';
        $operateurDest = $this->request->getGet('operateur_dest') ?? '';

        $whereGains = ["t.libelle IN ('Retrait', 'Transfert')"];
        $paramsGains = [];

        if ($typeOperation !== '') {
            $whereGains[] = "t.libelle = ?";
            $paramsGains[] = $typeOperation;
        }

        if ($operateurSource !== '') {
            $whereGains[] = "po.code_prefixe = ?";
            $paramsGains[] = $operateurSource;
        }

        $whereGainsSql = 'WHERE ' . implode(' AND ', $whereGains);

        $sqlGains = "
            SELECT 
                t.id,
                t.libelle,
                COALESCE(SUM(o.frais_applique), 0) as total_frais,
                COUNT(o.id) as nombre_operations
            FROM type_operation t
            LEFT JOIN operation o ON o.id_type_operation = t.id
            LEFT JOIN client c ON o.id_client_emetteur = c.id
            LEFT JOIN prefixe_operateur po ON c.id_prefixe = po.id
            {$whereGainsSql}
            GROUP BY t.id, t.libelle
            ORDER BY t.id ASC
        ";

        $queryGains = $db->query($sqlGains, $paramsGains);
        $data['gains'] = $queryGains->getResult();

        $whereOperateurs = ["t.libelle = 'Transfert'"];
        $paramsOperateurs = [];

        if ($operateurSource !== '') {
            $whereOperateurs[] = "po.code_prefixe = ?";
            $paramsOperateurs[] = $operateurSource;
        }

        $whereOperateursSql = 'WHERE ' . implode(' AND ', $whereOperateurs);

        $sqlOperateurs = "
            SELECT 
                po.id as operateur_id,
                po.operateur_nom,
                po.code_prefixe,
                COALESCE(SUM(o.frais_applique), 0) as total_frais,
                COUNT(o.id) as nombre_operations
            FROM operation o
            JOIN client c ON o.id_client_emetteur = c.id
            JOIN prefixe_operateur po ON c.id_prefixe = po.id
            JOIN type_operation t ON o.id_type_operation = t.id
            {$whereOperateursSql}
            GROUP BY po.id, po.operateur_nom, po.code_prefixe
            ORDER BY po.id ASC
        ";

        $queryOperateurs = $db->query($sqlOperateurs, $paramsOperateurs);
        $data['gains_operateurs'] = $queryOperateurs->getResult();

        $whereCommissions = ["t.libelle = 'Transfert'", "o.id_client_destinataire IS NOT NULL"];
        $paramsCommissions = [];

        if ($operateurDest !== '') {
            $whereCommissions[] = "po_dest.code_prefixe = ?";
            $paramsCommissions[] = $operateurDest;
        }

        $whereCommissionsSql = 'WHERE ' . implode(' AND ', $whereCommissions);

        $sqlCommissions = "
            SELECT 
                po_dest.id as operateur_id,
                po_dest.operateur_nom,
                po_dest.code_prefixe,
                COALESCE(SUM(o.montant * COALESCE(co.commission_pct, 0) / 100), 0) as total_commission
            FROM operation o
            JOIN client c_em ON o.id_client_emetteur = c_em.id
            JOIN client c_dest ON o.id_client_destinataire = c_dest.id
            JOIN prefixe_operateur po_dest ON c_dest.id_prefixe = po_dest.id
            LEFT JOIN commission_operateur co ON co.id_prefixe_source = c_em.id_prefixe AND co.id_prefixe_dest = c_dest.id_prefixe
            JOIN type_operation t ON o.id_type_operation = t.id
            {$whereCommissionsSql}
            GROUP BY po_dest.id, po_dest.operateur_nom, po_dest.code_prefixe
            ORDER BY po_dest.id ASC
        ";

        $queryCommissions = $db->query($sqlCommissions, $paramsCommissions);
        $data['commissions_operateurs'] = $queryCommissions->getResult();

        $operateurs = $db->query("SELECT id, operateur_nom, code_prefixe FROM prefixe_operateur ORDER BY id ASC")->getResult();
        $data['operateurs'] = $operateurs;
        $data['typeOperation'] = $typeOperation;
        $data['operateurSource'] = $operateurSource;
        $data['operateurDest'] = $operateurDest;

        $data['types'] = $db->query("SELECT id, libelle FROM type_operation WHERE libelle IN ('Retrait', 'Transfert') ORDER BY id ASC")->getResult();

        return view('statistique/gains', $data);
    }

    public function clients()
    {
        $db = \Config\Database::connect();

        $telephone = $this->request->getGet('telephone') ?? '';
        $prefixe = $this->request->getGet('prefixe') ?? '034';

        $where = ["u.role_id = 2", "p.code_prefixe = ?"];
        $params = ['034'];

        if ($prefixe !== '' && $prefixe !== '034') {
            $where[1] = "p.code_prefixe = ?";
            $params = [$prefixe];
        }

        if ($telephone !== '') {
            $where[] = "u.telephone LIKE ?";
            $params[] = "%" . $telephone . "%";
        }

        $whereSql = implode(' AND ', $where);

        $sql = "
            SELECT 
                u.id,
                u.telephone,
                c.solde,
                p.operateur_nom,
                p.code_prefixe,
                c.date_creation,
                COUNT(DISTINCT o.id) as nombre_operations
            FROM user u
            JOIN client c ON c.id_user = u.id
            LEFT JOIN prefixe_operateur p ON p.id = c.id_prefixe
            LEFT JOIN operation o ON o.id_client_emetteur = c.id OR o.id_client_destinataire = c.id
            WHERE {$whereSql}
            GROUP BY u.id, u.telephone, c.solde, p.operateur_nom, p.code_prefixe, c.date_creation
            ORDER BY u.id ASC
        ";

        $query = $db->query($sql, $params);
        $data['clients'] = $query->getResult();

        $operateurs = $db->query("SELECT id, operateur_nom, code_prefixe FROM prefixe_operateur ORDER BY id ASC")->getResult();
        $data['operateurs'] = $operateurs;
        $data['telephone'] = $telephone;
        $data['prefixe'] = $prefixe;

        return view('statistique/clients', $data);
    }
}
