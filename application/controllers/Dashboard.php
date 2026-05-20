<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Contact_model');
        $this->load->model('Anniversary_model');
    }

    /**
     * Dashboard index page
     */
    public function index() {
        $data['page_title'] = 'Dashboard';
        $data['total_contacts'] = $this->Contact_model->get_total_count();
        $data['upcoming_anniversaries'] = $this->Anniversary_model->get_upcoming(30);
        $data['recent_contacts'] = array_slice($this->Contact_model->get_all(), 0, 5);
        
        // Calculate statistics
        $all_anniversaries = $this->Anniversary_model->get_all();
        $data['total_anniversaries'] = count($all_anniversaries);
        
        $this->load->view('layout/header', $data);
        $this->load->view('dashboard', $data);
        $this->load->view('layout/footer');
    }
}
?>