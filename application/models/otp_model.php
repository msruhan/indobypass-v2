<?php
class Otp_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function generate_otp($user_id, $email) {
        // Generate 6-digit OTP
        $otp_code = sprintf("%06d", mt_rand(1, 999999));
        
        // Set expiration time (10 minutes from now)
        $expires_at = date('Y-m-d H:i:s', strtotime('+10 minutes'));
        
        // Invalidate any existing OTP for this user
        $this->db->where('user_id', $user_id);
        $this->db->where('is_used', 0);
        $this->db->update('user_otp', array('is_used' => 1));
        
        // Insert new OTP
        $data = array(
            'user_id' => $user_id,
            'email' => $email,
            'otp_code' => $otp_code,
            'expires_at' => $expires_at,
            'is_used' => 0
        );
        
        $this->db->insert('user_otp', $data);
        
        return $otp_code;
    }
    
    public function verify_otp($user_id, $otp_code) {
        $this->db->where('user_id', $user_id);
        $this->db->where('otp_code', $otp_code);
        $this->db->where('is_used', 0);
        $this->db->where('expires_at >', date('Y-m-d H:i:s'));
        $query = $this->db->get('user_otp');
        
        if ($query->num_rows() > 0) {
            // Mark OTP as used
            $this->db->where('user_id', $user_id);
            $this->db->where('otp_code', $otp_code);
            $this->db->update('user_otp', array('is_used' => 1));
            
            return true;
        }
        
        return false;
    }
}
?>