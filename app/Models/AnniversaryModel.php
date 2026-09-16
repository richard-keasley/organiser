<?php

namespace App\Models;

use CodeIgniter\Model;

class AnniversaryModel extends Model
{
    protected $table = 'anniversaries';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['contact_id', 'event_date', 'title', 'notes'];

    public function getAll(): array
    {
        if (! $this->db->tableExists($this->table)) {
            return [];
        }

        return $this->orderBy('event_date', 'ASC')->findAll();
    }

    public function getByContact(int $contactId): array
    {
        if (! $this->db->tableExists($this->table)) {
            return [];
        }

        return $this->where('contact_id', $contactId)->orderBy('event_date', 'ASC')->findAll();
    }

    public function getUpcoming(int $days = 30): array
    {
        if (! $this->db->tableExists($this->table)) {
            return [];
        }

        $start = date('Y-m-d');
        $end   = date('Y-m-d', strtotime('+' . $days . ' days'));

        return $this->where('event_date >=', $start)
            ->where('event_date <=', $end)
            ->orderBy('event_date', 'ASC')
            ->findAll();
    }
}
