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

        $sqlGains = "
            SELECT 
                t.id,
                t.libelle,
                COALESCE(SUM(
                    CASE 
                        WHEN t.libelle = 'Transfert' THEN o.frais_applique - (o.montant * COALESCE(co.commission_pct, 0) / 100)
                        ELSE o.frais_applique
                    END
                ), 0) as total_frais,
                COUNT(o.id) as nombre_operations
            FROM type_operation t
            LEFT JOIN operation o ON o.id_type_operation = t.id
            LEFT JOIN client c_em ON o.id_client_emetteur = c_em.id
            LEFT JOIN client c_dest ON o.id_client_destinataire = c_dest.id
            LEFT JOIN commission_operateur co ON co.id_prefixe_source = c_em.id_prefixe AND co.id_prefixe_dest = c_dest.id_prefixe
            WHERE t.libelle IN ('Retrait', 'Transfert')
            GROUP BY t.id, t.libelle
            ORDER BY t.id ASC
        ";

        $queryGains = $db->query($sqlGains);
        $data['gains'] = $queryGains->getResult();

        $sqlOperateurs = "
            SELECT 
                po.id as operateur_id,
                po.operateur_nom,
                po.code_prefixe,
                COALESCE(SUM(o.frais_applique - (o.montant * COALESCE(co.commission_pct, 0) / 100)), 0) as total_frais,
                COUNT(o.id) as nombre_operations
            FROM operation o
            JOIN client c ON o.id_client_emetteur = c.id
            JOIN client c_dest ON o.id_client_destinataire = c_dest.id
            JOIN prefixe_operateur po ON c.id_prefixe = po.id
            LEFT JOIN commission_operateur co ON co.id_prefixe_source = c.id_prefixe AND co.id_prefixe_dest = c_dest.id_prefixe
            JOIN type_operation t ON o.id_type_operation = t.id
            WHERE t.libelle = 'Transfert'
            GROUP BY po.id, po.operateur_nom, po.code_prefixe
            ORDER BY po.id ASC
        ";

        $queryOperateurs = $db->query($sqlOperateurs);
        $data['gains_operateurs'] = $queryOperateurs->getResult();

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
            WHERE t.libelle = 'Transfert' AND o.id_client_destinataire IS NOT NULL
            GROUP BY po_dest.id, po_dest.operateur_nom, po_dest.code_prefixe
            ORDER BY po_dest.id ASC
        ";

        $queryCommissions = $db->query($sqlCommissions);
        $data['commissions_operateurs'] = $queryCommissions->getResult();

        return view('statistique/gains', $data);
    }

    public function clients()
    {
        $db = \Config\Database::connect();

        $telephone = $this->request->getGet('telephone') ?? '';
        $prefixe = $this->request->getGet('prefixe') ?? '';

        $where = ["u.role_id = 2"];
        $params = [];

        if ($telephone !== '') {
            $where[] = "u.telephone LIKE ?";
            $params[] = "%" . $telephone . "%";
        }

        if ($prefixe !== '') {
            $where[] = "p.code_prefixe = ?";
            $params[] = $prefixe;
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
