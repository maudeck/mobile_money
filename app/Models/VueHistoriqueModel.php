<?php

namespace App\Models;

use CodeIgniter\Model;

class VueHistoriqueModel extends Model
{
    protected $table = 'vue_historique';
    protected $primaryKey = 'id';
    protected $allowedFields = ['date_operation', 'montant', 'frais_applique', 'type_operation', 'emetteur_telephone', 'destinataire_telephone'];
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = false;

    public function getHistoriqueByTelephone(string $telephone)
    {
        return $this->where('emetteur_telephone', $telephone)
            ->orWhere('destinataire_telephone', $telephone)
            ->orderBy('date_operation', 'DESC')
            ->findAll();
    }
}
