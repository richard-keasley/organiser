<?php

namespace App\Controllers;

use App\Models\AnniversaryModel;
use App\Models\ContactModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $contactModel = model(ContactModel::class);
        $anniversaryModel = model(AnniversaryModel::class);

        $data = [
            'page_title' => 'Dashboard',
            'total_contacts' => $contactModel->getTotalCount(),
            'upcoming_anniversaries' => $anniversaryModel->getUpcoming(30),
            'recent_contacts' => $contactModel->getAll(),
            'total_anniversaries' => count($anniversaryModel->getAll()),
        ];

        $data['recent_contacts'] = array_slice($data['recent_contacts'], 0, 5);

        return view('layout/header', $data)
            . view('dashboard', $data)
            . view('layout/footer');
    }
}
