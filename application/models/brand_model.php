<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class brand_model extends CI_Model
{

	public function __construct()
	{
		parent:: __construct();
		$this->tbl_name="gsm_brands";
		$this->tbl_models="gsm_models";
	}
	
	public function get_where($params) 
	{
        $query = $this->db->get_where($this->tbl_name, $params);
        return $query->result_array();
    }    
	
	public function get_provider($params = false)
	{
		$query = $this->db->get_where("gsm_provider", $params);
        return $query->result_array();
	}
	

    public function get_all() 
    {                
        $query = $this->db->get($this->tbl_name);
        return $query->result_array();
    }
    
    public function count_all() 
    {
        $query = $this->db->count_all($this->tbl_name);
        return $query;
    }

    public function insert($data) 
    {    	
        $this->db->insert($this->tbl_name, $data);
        $id = $this->db->insert_id();
        return intval($id);
    }
	
	public function truncate_mep()
	{
		$this->db->truncate('gsm_mep');
	}
	
	public function insert_batch_models($data)
	{
		$this->db->insert_batch($this->tbl_models, $data);
	}
	
	public function delete_batch_model($tool_id)
	{
		$this->db->delete($this->tbl_models, array('ToolID' => $tool_id));
	}
	
	public function delete_batch_provider($tool_id)
	{
		$this->db->delete("gsm_provider", array('ToolID' => $tool_id));
	}
	
	public function insert_batch_mep($data)
	{
		$this->db->insert_batch("gsm_mep", $data);
	}

    public function update($data, $id)
    {
    	   
        $this->db->update($this->tbl_name, $data, array('BrandID' => $id));
    }
	
	public function update_roles($data, $id)
	{
		$this->db->update( "hr_modules_access", $data, array('ID' => $id));
	}
	
	public function disabled_roles($data, $brandid)
	{		
		$this->db->update( "hr_modules_access", $data, array('brandID' => $brandid));
	}

	public function get_active_model($params)
	{
		$query = $this->db->get_where("gsm_methods", $params);
        return $query->result_array();
	}
	
	public function get_model_by_method_id($method_id)
	{
		$models = [];
		$this->db->select("BrandID, Title")
		->from($this->tbl_name)
		->where('MethodID', $method_id);
		$query = $this->db->get();
		$brands = $query->result_array();
		foreach ($brands as $k => $b) 
		{
			$query->free_result(); // The $query result object will no longer be available
			$models[$k]['ID'] = $b['BrandID'];
			$models[$k]['Title'] = $b['Title'];
			
			$this->db->select("ModelID, BrandID, Title, ApiBrandID");
			$this->db->from("$this->tbl_models");
			$this->db->where("BrandID", $b['BrandID']);
			$this->db->where("MethodID", $method_id);
			$this->db->where("Status", 'Enabled');
			$query = $this->db->get();
			$models[$k]['models'] = $query->result_array();
		}
		return $models;
	}	

    public function delete_by_method_id($id)
    {
		$this->db->delete($this->tbl_models, array('MethodID' => $id));
        $this->db->delete($this->tbl_name, array('MethodID' => $id));                
    }	
	
    public function delete($id)
    {
        $this->db->delete($this->tbl_name, array('BrandID' => $id));                
    }
	
	function get_datatable($access)
	{
		$this->load->library('datatables');
		$oprations = '';
		if($access['edit'] == 'Y')
			$oprations .= '<a href="'.site_url("admin/brand/edit/$1").'" title="Edit this record" class="tip"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
		if($access['delete'] == 'Y')
			$oprations .= '<a href="'.site_url("admin/brand/delete/$1").'" title="Delete this record" class="tip" onclick="return confirm(\'Are sure want to delete this record?\');"><i class="fa fa-trash-o" aria-hidden="true"></i></a>';
		
		$this->datatables
				->select("BrandID, Title, Status, UpdatedDateTime, CreatedDateTime", TRUE)
				->from($this->tbl_name)
				->add_column('delete', $oprations, 'BrandID');		
		return $this->datatables->generate();
	}	
}