<?php

namespace App\Models;

use CodeIgniter\Model;

class TrancheFraisModel extends Model
{
    protected $table = 'tranche_frais';
    protected $primaryKey = 'id';
    protected $allowedFields = ['montant_min', 'montant_max', 'frais', 'id_type_operation'];
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = false;

    public function getByTypeOperation($idTypeOperation)
    {
        return $this->where('id_type_operation', $idTypeOperation)
                    ->orderBy('montant_min', 'ASC')
                    ->findAll();
    }

    public function deleteByTypeOperation($idTypeOperation)
    {
        return $this->where('id_type_operation', $idTypeOperation)->delete();
    }
}
