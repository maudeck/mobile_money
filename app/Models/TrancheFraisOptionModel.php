<?php

namespace App\Models;

use CodeIgniter\Model;

class TrancheFraisOptionModel extends Model
{
    protected $table = 'tranche_frais_option';
    protected $primaryKey = 'id';
    protected $allowedFields = ['montant_min', 'montant_max', 'frais_option', 'id_type_operation'];
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = false;

    public function getByTypeOperation(int $idType): array
    {
        return $this->where('id_type_operation', $idType)
            ->orderBy('montant_min', 'ASC')
            ->findAll();
    }
}
