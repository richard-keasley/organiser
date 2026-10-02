<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactModel extends Model
{
    protected $table = 'contacts';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['first_name', 'last_name', 'email', 'phone', 'address', 'city', 'country', 'notes'];
    protected $useTimestamps = false;
    protected $validationRules = [
        'first_name' => 'required|min_length[2]',
        'last_name'  => 'required|min_length[2]',
        'email'      => 'permit_empty|valid_email',
    ];

    public function getAll($search = null): array
    {
        $builder = $this->db->table($this->table);

        if ($search) {
            $builder->groupStart()
                ->like('first_name', $search)
                ->orLike('last_name', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        return $builder->orderBy('first_name', 'ASC')->get()->getResultArray();
    }

    public function getContact($id): ?array
    {
        return $this->find($id);
    }

    public function getContactWithDetails(int $id): ?array
    {
        $contact = $this->find($id);

        if (! $contact) {
            return null;
        }

        $contact['phones'] = model(ContactPhoneNumberModel::class)->getByContact($id);
        $contact['emails'] = model(ContactEmailAddressModel::class)->getByContact($id);
        $contact['addresses'] = model(ContactPostalAddressModel::class)->getByContact($id);

        return $contact;
    }

    public function createContact(array $data): bool
    {
        return $this->insert($data) !== false;
    }

    public function updateContact(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    public function deleteContact(int $id): bool
    {
        return $this->delete($id);
    }

    public function getTotalCount(): int
    {
        return (int) $this->countAllResults();
    }
}
