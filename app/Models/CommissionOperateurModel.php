<?php

namespace App\Models;

use CodeIgniter\Model;

class CommissionOperateurModel extends Model
{
    protected $table = 'commission_operateur';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_prefixe_source', 'id_prefixe_dest', 'commission_pct'];
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = false;

    public function getAll()
    {
        return $this->select('commission_operateur.*, po1.operateur_nom as operateur_source, po2.operateur_nom as operateur_dest')
                    ->join('prefixe_operateur po1', 'po1.id = commission_operateur.id_prefixe_source')
                    ->join('prefixe_operateur po2', 'po2.id = commission_operateur.id_prefixe_dest')
                    ->orderBy('commission_operateur.id', 'DESC')
                    ->findAll();
    }

    public function getByOperateurs($idSource, $idDest)
    {
        return $this->where('id_prefixe_source', $idSource)
                    ->where('id_prefixe_dest', $idDest)
                    ->findAll();
    }

    public function findByOperateurs($idSource, $idDest)
    {
        return $this->where('id_prefixe_source', $idSource)
                    ->where('id_prefixe_dest', $idDest)
                    ->first();
    }
}
