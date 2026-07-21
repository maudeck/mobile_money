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
                COALESCE(SUM(o.frais_applique), 0) as total_frais,
                COUNT(o.id) as nombre_operations
            FROM type_operation t
            LEFT JOIN operation o ON o.id_type_operation = t.id
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
                COALESCE(SUM(o.frais_applique), 0) as total_frais,
                COUNT(o.id) as nombre_operations
            FROM operation o
            JOIN client c ON o.id_client_emetteur = c.id
            JOIN user u ON c.id_user = u.id
            JOIN prefixe_operateur po ON c.id_prefixe = po.id
            JOIN type_operation t ON o.id_type_operation = t.id
            WHERE t.libelle = 'Transfert'
            GROUP BY po.id, po.operateur_nom, po.code_prefixe
            ORDER BY po.id ASC
        ";

        $queryOperateurs = $db->query($sqlOperateurs);
        $data['gains_operateurs'] = $queryOperateurs->getResult();

        $sqlCommissions = "
            SELECT 
                po.id as operateur_id,
                po.operateur_nom,
                po.code_prefixe,
                COALESCE(SUM(o.frais_applique), 0) as total_commission
            FROM operation o
            JOIN client c ON o.id_client_destinataire = c.id
            JOIN user u ON c.id_user = u.id
            JOIN prefixe_operateur po ON c.id_prefixe = po.id
            JOIN type_operation t ON o.id_type_operation = t.id
            WHERE t.libelle = 'Transfert' AND o.id_client_destinataire IS NOT NULL
            GROUP BY po.id, po.operateur_nom, po.code_prefixe
            ORDER BY po.id ASC
        ";

        $queryCommissions = $db->query($sqlCommissions);
        $data['commissions_operateurs'] = $queryCommissions->getResult();

        return view('statistique/gains', $data);
    }

    public function clients()
    {
        $db = \Config\Database::connect();

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
            WHERE u.role_id = 2
            GROUP BY u.id, u.telephone, c.solde, p.operateur_nom, p.code_prefixe, c.date_creation
            ORDER BY u.id ASC
        ";

        $query = $db->query($sql);
        $data['clients'] = $query->getResult();

        return view('statistique/clients', $data);
    }
}
