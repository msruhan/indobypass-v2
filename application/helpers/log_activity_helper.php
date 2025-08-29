<?php
if (!function_exists('log_activity')) {
    function log_activity($activity, $user_id = null, $username = null) {
        $CI =& get_instance();
        $CI->load->database();
        if (!$user_id) {
            $user_id = $CI->session->userdata('user_id');
        }
        if (!$username) {
            $username = $CI->session->userdata('username');
        }
        $ip = $CI->input->ip_address();
        $CI->db->insert('activity_log', [
            'user_id' => $user_id,
            'username' => $username,
            'activity' => $activity,
            'ip_address' => $ip,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}
