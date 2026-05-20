<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Contact_model');
        $this->load->model('Anniversary_model');
        $this->load->library('form_validation');
    }

    /**
     * List all contacts
     */
    public function index() {
        $search = $this->input->get('search');
        $data['page_title'] = 'Contacts';
        $data['contacts'] = $this->Contact_model->get_all($search);
        $data['search_query'] = $search;
        
        $this->load->view('layout/header', $data);
        $this->load->view('contacts/index', $data);
        $this->load->view('layout/footer');
    }

    /**
     * View single contact
     */
    public function view($id) {
        $data['page_title'] = 'View Contact';
        $data['contact'] = $this->Contact_model->get_contact($id);
        
        if (!$data['contact']) {
            show_404();
        }
        
        $data['anniversaries'] = $this->Anniversary_model->get_by_contact($id);
        
        $this->load->view('layout/header', $data);
        $this->load->view('contacts/view', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Create new contact
     */
    public function create() {
        $data['page_title'] = 'Add New Contact';
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[2]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[2]');
            $this->form_validation->set_rules('email', 'Email', 'valid_email');
            
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layout/header', $data);
                $this->load->view('contacts/create', $data);
                $this->load->view('layout/footer');
            } else {
                $contact_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'city' => $this->input->post('city'),
                    'country' => $this->input->post('country'),
                    'notes' => $this->input->post('notes')
                );
                
                if ($this->Contact_model->create_contact($contact_data)) {
                    $this->session->set_flashdata('success', 'Contact created successfully!');
                    redirect('contacts');
                }
            }
        } else {
            $this->load->view('layout/header', $data);
            $this->load->view('contacts/create', $data);
            $this->load->view('layout/footer');
        }
    }

    /**
     * Edit contact
     */
    public function edit($id) {
        $data['page_title'] = 'Edit Contact';
        $data['contact'] = $this->Contact_model->get_contact($id);
        
        if (!$data['contact']) {
            show_404();
        }
        
        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required|min_length[2]');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|min_length[2]');
            $this->form_validation->set_rules('email', 'Email', 'valid_email');
            
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('layout/header', $data);
                $this->load->view('contacts/edit', $data);
                $this->load->view('layout/footer');
            } else {
                $contact_data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email' => $this->input->post('email'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'city' => $this->input->post('city'),
                    'country' => $this->input->post('country'),
                    'notes' => $this->input->post('notes')
                );
                
                if ($this->Contact_model->update_contact($id, $contact_data)) {
                    $this->session->set_flashdata('success', 'Contact updated successfully!');
                    redirect('contacts/view/' . $id);
                }
            }
        } else {
            $this->load->view('layout/header', $data);
            $this->load->view('contacts/edit', $data);
            $this->load->view('layout/footer');
        }
    }

    /**
     * Delete contact
     */
    public function delete($id) {
        if ($this->Contact_model->delete_contact($id)) {
            $this->session->set_flashdata('success', 'Contact deleted successfully!');
            redirect('contacts');
        }
    }
}
?>