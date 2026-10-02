<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactPhoneNumberModel extends Model
{
    protected $table = 'contact_phone_numbers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['contact_id', 'phone_number', 'phone_type', 'is_primary'];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    public function getByContact(int $contactId): array
    {
        if (! $this->db->tableExists($this->table)) {
            return [];
        }

        return $this->where('contact_id', $contactId)
            ->orderBy('is_primary', 'DESC')
            ->orderBy('phone_type', 'ASC')
            ->findAll();
    }

    public function getPrimaryForContact(int $contactId): ?array
    {
        if (! $this->db->tableExists($this->table)) {
            return null;
        }

        return $this->where('contact_id', $contactId)
            ->where('is_primary', 1)
            ->first();
    }
}
