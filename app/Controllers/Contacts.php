<?php

namespace App\Controllers;

use App\Models\AnniversaryModel;
use App\Models\ContactEmailAddressModel;
use App\Models\ContactModel;
use App\Models\ContactPhoneNumberModel;
use App\Models\ContactPostalAddressModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Contacts extends BaseController
{
    protected ContactModel $contactModel;
    protected AnniversaryModel $anniversaryModel;

    public function __construct()
    {
        $this->contactModel = model(ContactModel::class);
        $this->anniversaryModel = model(AnniversaryModel::class);
    }

    public function index(): string
    {
        $search = $this->request->getGet('search');

        $data = [
            'page_title' => 'Contacts',
            'contacts' => $this->contactModel->getAll($search),
            'search_query' => $search,
        ];

        return view('layout/header', $data)
            . view('contacts/index', $data)
            . view('layout/footer');
    }

    public function view(int $id): string
    {
        $contact = $this->contactModel->getContactWithDetails($id);

        if (! $contact) {
            throw new PageNotFoundException('Contact not found');
        }

        $data = [
            'page_title' => 'View Contact',
            'contact' => $contact,
            'anniversaries' => $this->anniversaryModel->getByContact($id),
        ];

        return view('layout/header', $data)
            . view('contacts/view', $data)
            . view('layout/footer');
    }

    public function create(): string
    {
        $data = ['page_title' => 'Add New Contact'];

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'first_name' => 'required|min_length[2]',
                'last_name'  => 'required|min_length[2]',
                'email'      => 'permit_empty|valid_email',
            ];

            if (! $this->validate($rules)) {
                $data['validation'] = $this->validator;
                return view('layout/header', $data)
                    . view('contacts/create', $data)
                    . view('layout/footer');
            }

            $contactData = [
                'first_name' => $this->request->getPost('first_name'),
                'last_name'  => $this->request->getPost('last_name'),
                'email'      => $this->request->getPost('email'),
                'phone'      => $this->request->getPost('phone'),
                'address'    => $this->request->getPost('address'),
                'city'       => $this->request->getPost('city'),
                'country'    => $this->request->getPost('country'),
                'notes'      => $this->request->getPost('notes'),
            ];

            $contactId = $this->contactModel->insert($contactData);

            if ($contactId) {
                $this->saveLinkedDetails(
                    (int) $contactId,
                    $this->request->getPost('phones') ?? [],
                    $this->request->getPost('emails') ?? [],
                    $this->request->getPost('addresses') ?? []
                );

                session()->setFlashdata('success', 'Contact created successfully!');
                return redirect()->to('/contacts');
            }
        }

        return view('layout/header', $data)
            . view('contacts/create', $data)
            . view('layout/footer');
    }

    public function edit(int $id): string
    {
        $contact = $this->contactModel->getContact($id);

        if (! $contact) {
            throw new PageNotFoundException('Contact not found');
        }

        $data = [
            'page_title' => 'Edit Contact',
            'contact' => $this->contactModel->getContactWithDetails($id),
        ];

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'first_name' => 'required|min_length[2]',
                'last_name'  => 'required|min_length[2]',
                'email'      => 'permit_empty|valid_email',
            ];

            if (! $this->validate($rules)) {
                $data['validation'] = $this->validator;
                return view('layout/header', $data)
                    . view('contacts/edit', $data)
                    . view('layout/footer');
            }

            $contactData = [
                'first_name' => $this->request->getPost('first_name'),
                'last_name'  => $this->request->getPost('last_name'),
                'email'      => $this->request->getPost('email'),
                'phone'      => $this->request->getPost('phone'),
                'address'    => $this->request->getPost('address'),
                'city'       => $this->request->getPost('city'),
                'country'    => $this->request->getPost('country'),
                'notes'      => $this->request->getPost('notes'),
            ];

            if ($this->contactModel->update($id, $contactData)) {
                $this->saveLinkedDetails(
                    $id,
                    $this->request->getPost('phones') ?? [],
                    $this->request->getPost('emails') ?? [],
                    $this->request->getPost('addresses') ?? []
                );

                session()->setFlashdata('success', 'Contact updated successfully!');
                return redirect()->to('/contacts/view/' . $id);
            }
        }

        return view('layout/header', $data)
            . view('contacts/edit', $data)
            . view('layout/footer');
    }

    public function delete(int $id): \CodeIgniter\HTTP\RedirectResponse
    {
        $this->contactModel->deleteContact($id);
        session()->setFlashdata('success', 'Contact deleted successfully!');

        return redirect()->to('/contacts');
    }

    protected function saveLinkedDetails(int $contactId, array $phones, array $emails, array $addresses): void
    {
        $phoneModel = model(ContactPhoneNumberModel::class);
        $emailModel = model(ContactEmailAddressModel::class);
        $addressModel = model(ContactPostalAddressModel::class);

        $phoneModel->where('contact_id', $contactId)->delete();
        $emailModel->where('contact_id', $contactId)->delete();
        $addressModel->where('contact_id', $contactId)->delete();

        foreach ($this->normalisePhones($phones) as $row) {
            $phoneModel->insert([
                'contact_id' => $contactId,
                'phone_number' => $row['phone_number'],
                'phone_type' => $row['phone_type'],
                'is_primary' => $row['is_primary'] ? 1 : 0,
            ]);
        }

        foreach ($this->normaliseEmails($emails) as $row) {
            $emailModel->insert([
                'contact_id' => $contactId,
                'email_address' => $row['email_address'],
                'email_type' => $row['email_type'],
                'is_primary' => $row['is_primary'] ? 1 : 0,
            ]);
        }

        foreach ($this->normaliseAddresses($addresses) as $row) {
            $addressModel->insert([
                'contact_id' => $contactId,
                'street_address' => $row['street_address'],
                'city' => $row['city'],
                'state_province' => $row['state_province'],
                'postal_code' => $row['postal_code'],
                'country' => $row['country'],
                'address_type' => $row['address_type'],
                'is_primary' => $row['is_primary'] ? 1 : 0,
            ]);
        }
    }

    private function normalisePhones(array $entries): array
    {
        $items = [];

        foreach ($entries as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $number = trim((string) ($entry['phone_number'] ?? ''));
            if ($number === '') {
                continue;
            }

            $items[] = [
                'phone_number' => $number,
                'phone_type' => trim((string) ($entry['phone_type'] ?? 'Mobile')) ?: 'Mobile',
                'is_primary' => $this->isPrimaryChecked($entry['is_primary'] ?? false),
            ];
        }

        return $this->ensurePrimary($items);
    }

    private function normaliseEmails(array $entries): array
    {
        $items = [];

        foreach ($entries as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $email = trim((string) ($entry['email_address'] ?? ''));
            if ($email === '') {
                continue;
            }

            $items[] = [
                'email_address' => $email,
                'email_type' => trim((string) ($entry['email_type'] ?? 'Personal')) ?: 'Personal',
                'is_primary' => $this->isPrimaryChecked($entry['is_primary'] ?? false),
            ];
        }

        return $this->ensurePrimary($items);
    }

    private function normaliseAddresses(array $entries): array
    {
        $items = [];

        foreach ($entries as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $street = trim((string) ($entry['street_address'] ?? ''));
            $city = trim((string) ($entry['city'] ?? ''));
            $country = trim((string) ($entry['country'] ?? ''));

            if ($street === '' && $city === '' && $country === '') {
                continue;
            }

            $items[] = [
                'street_address' => $street,
                'city' => $city,
                'state_province' => trim((string) ($entry['state_province'] ?? '')),
                'postal_code' => trim((string) ($entry['postal_code'] ?? '')),
                'country' => $country,
                'address_type' => trim((string) ($entry['address_type'] ?? 'Home')) ?: 'Home',
                'is_primary' => $this->isPrimaryChecked($entry['is_primary'] ?? false),
            ];
        }

        return $this->ensurePrimary($items);
    }

    private function ensurePrimary(array $items): array
    {
        if ($items === []) {
            return [];
        }

        $hasPrimary = false;
        foreach ($items as $item) {
            if (! empty($item['is_primary'])) {
                $hasPrimary = true;
                break;
            }
        }

        if (! $hasPrimary) {
            $items[0]['is_primary'] = true;
        }

        return $items;
    }

    private function isPrimaryChecked($value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value)) {
            return (int) $value === 1;
        }

        return in_array(strtolower((string) $value), ['1', 'true', 'on', 'yes'], true);
    }
}
