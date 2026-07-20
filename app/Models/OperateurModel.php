<?php

namespace App\Models;

use CodeIgniter\Model;

class OperateurModel extends Model
{
    protected $table = 'prefixe_operateur';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code_prefixe', 'operateur_nom'];
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = false;

    public function getAll()
    {
        return $this->orderBy('id', 'ASC')->findAll();
    }
}
