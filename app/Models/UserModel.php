<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['telephone', 'role_id'];
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useTimestamps = false;

    public function findByTelephone(string $telephone): ?object
    {
        $result = $this->where('telephone', $telephone)->first();
        return $result ?: null;
    }

    public function createClient(string $telephone): object
    {
        $this->insert([
            'telephone' => $telephone,
            'role_id' => 2
        ]);
        return $this->findByTelephone($telephone);
    }
}
