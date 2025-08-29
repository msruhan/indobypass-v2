<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Log_model');
        if (!$this->session->userdata('is_admin_logged_in')) {
            redirect('admin/session/login');
        }
    }

    public function index() {
        $data['module_name'] = 'Log Aktivitas';
        $data['template'] = 'admin/log/list';
        $data['logs'] = $this->Log_model->get_logs();
        $this->load->view('admin/master_template', $data);
    }
}
