<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_model extends CI_Model {
    public function get_logs($limit = 100) {
        $this->db->order_by('created_at', 'DESC');
        $query = $this->db->get('activity_log', $limit);
        return $query->result();
    }
}
