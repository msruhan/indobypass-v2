<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class aiagent extends FSD_Controller 
{
    var $before_filter = array('name' => 'member_authorization', 'except' => array());

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = array();
        $data['Title'] = "AI Agent";
        $data['template'] = "member/aiagent";
        $data['content'] = "member/aiagent";
        $data['content_js'] = "dashboard/dashboard.js";
        $this->load->view('mastertemplate', $data);
    }
}
