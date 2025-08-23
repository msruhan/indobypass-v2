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
	   // Jika ada ?all=1, return semua group tanpa paging/filter
	   if ($this->input->get('all') == '1') {
		   $all = $this->network_model->get_all();
		   $data = array();
		   foreach ($all as $row) {
			   $row['delete'] =
				   '<a href="'.site_url('admin/network/edit/'.$row['ID']).'" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>';
			   $row['delete'] .=
				   ' <a href="'.site_url('admin/network/delete/'.$row['ID']).'" class="btn btn-danger btn-sm" onclick="return confirm(\'Delete this network?\')"><i class="fa fa-trash"></i></a>';
			   $data[] = $row;
		   }
		   echo json_encode(array('data' => $data));
		   return;
	   }
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
	/**
	 * Bulk update Description, Download, Video untuk banyak service sekaligus
	 * Endpoint: POST JSON { ids: [id1, id2, ...], Description, Download, Video }
	 */
	public function bulk_update_services()
	{
		$input = json_decode(file_get_contents('php://input'), true);
		if (!$input || !isset($input['ids']) || !is_array($input['ids'])) {
			http_response_code(400);
			echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
			return;
		}
		$ids = $input['ids'];
		$data = [];
		if (isset($input['Description'])) $data['Description'] = $input['Description'];
		if (isset($input['Download'])) $data['Download'] = $input['Download'];
		if (isset($input['Video'])) $data['Video'] = $input['Video'];
		if (empty($data)) {
			http_response_code(400);
			echo json_encode(['status' => 'error', 'message' => 'No data to update']);
			return;
		}
		$this->load->model('method_model');
		$updated = 0;
		foreach ($ids as $id) {
			$this->method_model->update($data, $id);
			$updated++;
		}
		echo json_encode(['status' => 'success', 'updated' => $updated]);
	}

	// Tukar nilai ID antar group
	public function swap_id() {
		$id1 = $this->input->post('id1');
		$id2 = $this->input->post('id2');
		if (!$id1 || !$id2) {
			show_error('ID tidak valid', 400);
			return;
		}
		// Ambil data group berdasarkan ID
		$group1 = $this->network_model->get_where(array('ID' => $id1));
		$group2 = $this->network_model->get_where(array('ID' => $id2));
		if (!$group1 || !$group2) {
			show_error('Group tidak ditemukan', 404);
			return;
		}
		// Proses swap ID
		$this->db->trans_start();
		// Cari ID sementara yang benar-benar unik
		$maxId = $this->db->select_max('ID')->get('gsm_networks')->row()->ID;
		$tempId = $maxId + 1000000 + rand(1,99999);
		// Swap ID group
		$this->network_model->update(array('ID' => $tempId), $id1);
		$this->network_model->update(array('ID' => $id1), $id2);
		$this->network_model->update(array('ID' => $id2), $tempId);
		// Setelah swap, update NetworkID pada gsm_methods dengan ID sementara agar tidak overwrite
		// 1. NetworkID = id1 -> tempId
		$this->db->where('NetworkID', $id1)->update('gsm_methods', array('NetworkID' => $tempId));
		// 2. NetworkID = id2 -> id1
		$this->db->where('NetworkID', $id2)->update('gsm_methods', array('NetworkID' => $id1));
		// 3. NetworkID = tempId -> id2
		$this->db->where('NetworkID', $tempId)->update('gsm_methods', array('NetworkID' => $id2));
		$this->db->trans_complete();
		if ($this->db->trans_status() === FALSE) {
			show_error('Gagal swap ID', 500);
		} else {
			echo json_encode(['status' => 'success']);
		}
	}
}

/* End of file network.php */
/* Location: ./application/controllers/admin/network.php */