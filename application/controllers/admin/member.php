
	
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends FSD_Controller 
{
	var $before_filter = array('name' => 'authorization', 'except' => array());
	var $access = array('view' => '', 'add' => '', 'edit' => '', 'delete' => '');
	var $module_name = 'Members Manager';
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('member_model');
		$this->load->model('method_model');
		$this->load->model('group_model');		
	}
	
	public function index()
	{
		$data['template'] = "admin/member/list";
		$this->load->view('admin/master_template',$data);
	}
	
	public function listener()
	{
		$json = $this->member_model->get_datatable($this->access);
		$raw = json_decode($json, true);
		if (isset($raw['data']) && is_array($raw['data'])) {
			foreach ($raw['data'] as &$row) {
				$row['delete'] =
					'<a href="'.site_url('admin/member/detail/'.$row['ID']).'" class="btn btn-info btn-sm" style="margin-right:4px;"><i class="fa fa-search"></i> Details</a>';
				$row['delete'] .=
					'<a href="'.site_url('admin/member/edit/'.$row['ID']).'" class="btn btn-warning btn-sm" style="margin-right:4px;"><i class="fa fa-edit"></i></a>';
				$row['delete'] .=
					'<a href="'.site_url('admin/member/delete/'.$row['ID']).'" class="btn btn-danger btn-sm" onclick="return confirm(\'Delete this member?\')"><i class="fa fa-trash"></i></a>';
			}
		}
		echo json_encode($raw);
	}

	public function add()
	{
		$group_list= array('' => '');
		foreach ($this->group_model->get_all() as $key => $value) 
		{
			$group_list[$value['ID']] = $value['Title'];
		}
		$data['group_list'] = $group_list;
		$data['template'] = "admin/member/add";
		$this->load->view('admin/master_template',$data);
	}

	public function edit($id)
	{
		$group_list= array('' => '');
		foreach ($this->group_model->get_all() as $key => $value) 
		{
			$group_list[$value['ID']] = $value['Title'];
		}
		$data['group_list'] = $group_list;		
		$data['data'] = $this->member_model->get_where(array('ID'=> $id));
		$data['template'] = "admin/member/edit";
		
		$this->load->view('admin/master_template',$data);
	}
		
	public function delete($id)
	{
		$this->member_model->delete($id);
		$this->session->set_flashdata('success', 'Record delete successfully.');
		redirect("admin/member/");
	}
	
	public function insert()
	{
		$this->load->library('form_validation');		
		
		$this->form_validation->set_rules('FirstName' , 'FirstName' ,'required');		
		$this->form_validation->set_rules('LastName' , 'LastName' ,'required');
		$this->form_validation->set_rules('Email' , 'Email' ,'required|email|is_unique[gsm_members.Email]');
		$this->form_validation->set_rules('Password' , 'Password' ,'required|min_length[6]');		
		$this->form_validation->set_rules('Mobile' , 'Mobile' ,'');
		if($this->form_validation->run() === FALSE)
		{
			$this->add();
		}
		else
		{
			$data = $this->input->post(NULL, TRUE);
			$data['UpdatedDateTime'] = date("Y-m-d H:i:s");
			$data['CreatedDateTime'] = date("Y-m-d H:i:s");
			$data['Status'] = isset($data['Status'])? 'Enabled':'Disabled';

			$this->member_model->insert($data);
			$this->session->set_flashdata('success', 'Record added successfully.');
			redirect("admin/member/");
		}
	}		

	################# Edit Imei Method Price Individually ############
	
	public function editmethodprice($id = 0)
	{
		$data['methods'] = $this->member_model->get_all_method_member($id);
		$data['MemberID'] = $id;
		$data['template'] = "admin/member/editmethodprice";
		$this->load->view('admin/master_template',$data);						
	}
	
	############## Edit File Method Price Individually ############
	public function editfilemethodprice($id = 0)
	{
		$data['file_methods'] = $this->member_model->get_all_file_member_price($id);
		$data['MemberID'] = $id;
		$data['template'] = "admin/member/editfilemethodprice";
		$this->load->view('admin/master_template',$data);
	}
	
	###### Save changes Individually IMEI Method Prices #############
	
	function membermethod()
	{
		$data = $this->input->post(NULL,TRUE);
		$this->member_model->delete_method($data['ID']);
		for($a=0; $a<count($data['Title']); $a++)
		{
			$insert = array(
			'MemberID' => $data['ID'],
			'MethodID' => $data['MethodID'][$a],
			'Price' => $data['Title'][$a]
			);
			$this->member_model->insert_method($insert);
		}
		$this->session->set_flashdata('success', 'Method Price edit successfully.');
		redirect("admin/member/");
		
	}
	
	###### Save changes Individually File Method Prices #############
	
	public function filemembermethod()
	{
		$data = $this->input->post(NULL,TRUE);
		$this->member_model->delete_filemethod($data['ID']);
		for($a=0; $a<count($data['Title']); $a++)
		{
			$insert = array(
			'MemberID' => $data['ID'],
			'FileServiceID' => $data['FileServiceID'][$a],
			'Price' => $data['Title'][$a]
			);
			$this->member_model->insert_filemethod($insert);
		}
		$this->session->set_flashdata('success', 'File Method Price edit successfully.');
		redirect("admin/member/");
	}

	public function update()
	{
		$this->load->library('form_validation');	
		$data = $this->input->post(NULL,TRUE);
		$id = $data['ID'];
							
		$this->form_validation->set_rules('FirstName' , 'FirstName' ,'required');		
		$this->form_validation->set_rules('LastName' , 'LastName' ,'required');
		$this->form_validation->set_rules('Email' , 'Email' ,'required|email');
		$this->form_validation->set_rules('Password' , 'Password' ,'min_length[6]');
		$this->form_validation->set_rules('Mobile' , 'Mobile' ,'');
		
		if($this->form_validation->run() === FALSE)
		{
			$this->edit($id);
		}
		else
		{
			unset($data['ID']);
			$data['UpdatedDateTime'] = date("Y-m-d H:i:s");
			$data['Status'] = isset($data['Status'])? 'Enabled':'Disabled';
			
			$this->member_model->update($data, $id);
			$this->session->set_flashdata('success', 'Record updated successfully.');
			redirect("admin/member/");
		}
	}

	public function status()
	{
		$id = $this->input->get('id');
		$status = $this->input->get('status');
		if( !empty($id) && !empty($status) )
		{
			$this->member_model->update(['Status' => $status], $id);
		}
		else
			echo 'All parameters are required.';
	}

	public function get_price_services_ajax($id)
	{
		$services = $this->member_model->get_all_method_member($id);
		$html = '<form id="form-edit-prices"><div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>Service</th><th>Price</th></tr></thead><tbody>';
		if ($services && is_array($services)) {
			foreach ($services as $srv) {
				$html .= '<tr>';
				$html .= '<td>' . htmlspecialchars($srv['Title']) . '<input type="hidden" name="MethodID[]" value="' . htmlspecialchars($srv['MethodID']) . '"></td>';
				$html .= '<td><input type="text" class="form-control input-sm" name="Price[]" value="' . htmlspecialchars($srv['Price']) . '" /></td>';
				$html .= '</tr>';
			}
		} else {
			$html .= '<tr><td colspan="2" class="text-center">No services found.</td></tr>';
		}
		$html .= '</tbody></table>';
		$html .= '<input type="hidden" name="MemberID" value="' . intval($id) . '">';
		$html .= '<div class="d-flex" style="gap:8px;">';
		$html .= '<button type="submit" class="btn btn-primary" id="btn-save-prices" style="margin-right:8px;">Simpan</button>';
		$html .= '<button type="button" class="btn btn-default" id="btn-default-prices" style="background:#eee;">Default Price</button>';
		$html .= '</div>';
		$html .= '</div></form>';
		echo $html;
	}

	public function update_method_prices()
	{
		$member_id = $this->input->post('MemberID');
		$method_ids = $this->input->post('MethodID');
		$prices = $this->input->post('Price');
		if (!$member_id || !is_array($method_ids) || !is_array($prices)) {
			echo json_encode(['success' => false, 'msg' => 'Data tidak valid']);
			return;
		}
		// Hapus semua custom price lama user ini
		$this->member_model->delete_method($member_id);
		// Insert ulang custom price baru
		$inserted = 0;
		for ($i = 0; $i < count($method_ids); $i++) {
			$method_id = $method_ids[$i];
			$price = $prices[$i];
			if ($price !== '' && is_numeric($price)) {
				$this->member_model->insert_method([
					'MemberID' => $member_id,
					'MethodID' => $method_id,
					'Price' => $price
				]);
				$inserted++;
			}
		}
		echo json_encode(['success' => true, 'inserted' => $inserted]);
	}


	public function get_default_prices_ajax($id)
	{
		// Ambil semua service yang tersedia untuk member ini
		$services = $this->member_model->get_all_method_member($id);
		$default_prices = [];
		if ($services && is_array($services)) {
			foreach ($services as $srv) {
				// Ambil harga default dari method_model (bukan custom)
				$default = $this->method_model->get_where(['ID' => $srv['MethodID']]);
				$default_price = isset($default[0]['Price']) ? $default[0]['Price'] : '';
				$default_prices[] = [
					'MethodID' => $srv['MethodID'],
					'Price' => $default_price
				];
			}
		}
		echo json_encode(['success' => true, 'data' => $default_prices]);
	}

}

/* End of file member.php */
/* Location: ./application/controllers/admin/member.php */