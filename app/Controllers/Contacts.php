<?php

namespace App\Controllers;

use App\Models\AnniversaryModel;
use App\Models\ContactModel;
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
        $contact = $this->contactModel->getContact($id);

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

            if ($this->contactModel->createContact($contactData)) {
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
            'contact' => $contact,
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

            if ($this->contactModel->updateContact($id, $contactData)) {
                session()->setFlashdata('success', 'Contact updated successfully!');
                return redirect()->to('/contacts/view/' . $id);
            }
        }

        return view('layout/header', $data)
            . view('contacts/edit', $data)
            . view('layout/footer');
    }

    public function delete(int $id): \
CodeIgniter\HTTP\RedirectResponse
    {
        $this->contactModel->deleteContact($id);
        session()->setFlashdata('success', 'Contact deleted successfully!');

        return redirect()->to('/contacts');
    }
}
