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

    public function createForUser(int $userId, ?int $prefixeId = null): object
    {
        return $this->insert([
            'id_user' => $userId,
            'solde' => 0,
            'date_creation' => date('Y-m-d H:i:s'),
            'id_prefixe' => $prefixeId
        ], true);
    }
}
