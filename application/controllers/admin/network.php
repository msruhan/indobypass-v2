<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Network extends FSD_Controller 
{
	var $before_filter = array('name' => 'authorization', 'except' => array());
	var $access = array('view' => '', 'add' => '', 'edit' => '', 'delete' => '');
	var $module_name = 'Service Groups Manager';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('network_model');
		$this->load->model('method_model');		
	}
	
	public function index()
	{
		$data['template'] = "admin/network/list";
		
		$this->load->view('admin/master_template',$data);
	}
	
	public function listener()
	{
		$json = $this->network_model->get_datatable($this->access);
		$raw = json_decode($json, true);
		if (isset($raw['data']) && is_array($raw['data'])) {
			foreach ($raw['data'] as &$row) {
				$row['delete'] =
					'<a href="'.site_url('admin/network/edit/'.$row['ID']).'" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
				$row['delete'] .=
					' <a href="'.site_url('admin/network/delete/'.$row['ID']).'" class="btn btn-danger btn-sm" onclick="return confirm(\'Delete this network?\')"><i class="fa fa-trash"></i></a>';
			}
		}
		echo json_encode($raw);
	}

	public function add()
	{
		$data['template'] = "admin/network/add";
		$this->load->view('admin/master_template',$data);
	}

	public function edit($id)
	{		
		$data['data'] = $this->network_model->get_where(array('ID'=> $id));
		$data['template'] = "admin/network/edit";
		$this->load->view('admin/master_template',$data);
	}
		
	public function delete($id)
	{
		$result = $this->method_model->count_where(array('NetworkID' => $id));
		if($result > 0)
		{
			$this->session->set_flashdata('warning', $result . ' method(s) are associated with this network.');
			redirect("admin/network/");			
		}
		$this->network_model->delete($id);
		$this->session->set_flashdata('success', 'Record delete successfully.');
		redirect("admin/network/");
	}
	
	public function insert()
	{
		$this->load->library('form_validation');				
		
		$this->form_validation->set_rules('Title' , 'Title' ,'required');			

		if($this->form_validation->run() === FALSE)
		{
			$this->add();
		}
		else
		{
			$data = $this->input->post(NULL,TRUE);	
			$data['UpdatedDateTime'] = date("Y-m-d H:i:s");
			$data['CreatedDateTime'] = date("Y-m-d H:i:s");
			
			$this->network_model->insert($data);
			$this->session->set_flashdata('success', 'Record added successfully.');
			redirect("admin/network/");
		}
	}		

	public function update()
	{
		$this->load->library('form_validation');
		$data = $this->input->post(NULL,TRUE);
		$id = $data['ID'];		
				
		$this->form_validation->set_rules('Title' , 'Title' ,'required');		
		if($this->form_validation->run() === FALSE)
		{
			$this->edit($id);
		}
		else
		{
			unset($data['ID']);					
			$data['UpdatedDateTime'] = date("Y-m-d H:i:s");
						
			$this->network_model->update($data, $id);
			$this->session->set_flashdata('success', 'Record updated successfully.');
			redirect("admin/network/");
		}
	}
}

/* End of file network.php */
/* Location: ./application/controllers/admin/network.php */