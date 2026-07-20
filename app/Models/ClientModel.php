<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'client';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_user', 'solde', 'date_creation', 'id_prefixe'];
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = false;

    public function findByUserId(int $userId): ?object
    {
        $result = $this->where('id_user', $userId)->first();
        return $result ?: null;
    }
}
