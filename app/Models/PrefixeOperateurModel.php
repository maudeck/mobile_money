<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeOperateurModel extends Model
{
    protected $table = 'prefixe_operateur';
    protected $primaryKey = 'id';
    protected $allowedFields = ['code_prefixe', 'operateur_nom'];
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = false;
}
