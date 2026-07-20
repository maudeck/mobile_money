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

        $sql = "
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

        $query = $db->query($sql);
        $data['gains'] = $query->getResult();

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
