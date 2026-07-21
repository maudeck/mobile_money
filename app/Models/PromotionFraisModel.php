<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionFraisModel extends Model
{
    protected $table = 'promotion_frais';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_prefixe_source', ' id_prefixe_dest', 'reduction_prc', 'id_type_operation'];
    protected $returnType = 'object';
    protected $useTimestamps = false;
}
