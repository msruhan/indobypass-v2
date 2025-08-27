<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class midtrans_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tbl_name = "midtrans_transaction";
    }

    public function get_deposit_data($memberID, $start, $length, $search)
    {
        $this->db->select('ID, MemberID, transactionCode, OrderID, Description, Amount, CreatedDateTime, TransactionStatus');
        $this->db->from($this->tbl_name);
        $this->db->where('MemberID', $memberID);
        if ($search) {
            $this->db->group_start();
            $this->db->like('OrderID', $search);
            $this->db->or_like('Description', $search);
            $this->db->or_like('Amount', $search);
            $this->db->or_like('TransactionStatus', $search);
            $this->db->or_like('CreatedDateTime', $search);
            $this->db->group_end();
        }
        $this->db->order_by('ID', 'DESC');
        $this->db->limit($length, $start);
        $query = $this->db->get();
        return $query->result_array();
    }
}
