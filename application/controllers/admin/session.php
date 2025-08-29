<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Session extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('employee_model');
		$this->load->model('autoresponder_model');
		$this->load->helper('log_activity_helper');
	}

	public function index()
	{
		if( $this->session->userdata('is_admin_logged_in') === FALSE)
			$this->load->view("admin/login");
		else
			redirect('admin');
	}

	public function login()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('Email', 'Email', 'trim|required|valid_email|min_length[4]');
		$this->form_validation->set_rules('Password', 'Password', 'trim|required|min_length[4]|max_length[32]');

		// --- Rate limit by IP ---
		$ip = $this->input->ip_address();
		$max_attempts = 5;
		$block_minutes = 15;
		$attempts_key = 'admin_login_attempts_' . md5($ip);
		$block_key = 'admin_login_block_' . md5($ip);
		$attempts = $this->session->userdata($attempts_key) ?: 0;
		$block_until = $this->session->userdata($block_key);
		if ($block_until && time() < $block_until) {
			$wait = ceil(($block_until - time()) / 60);
			$this->session->set_flashdata('error', 'Terlalu banyak percobaan login gagal. Silakan coba lagi dalam ' . $wait . ' menit.');
			redirect('admin/session');
		}

		// --- Google reCAPTCHA validation ---
		$recaptcha_response = $this->input->post('g-recaptcha-response');
		$recaptcha_secret = '6LdWw7QrAAAAAM-DXXUex0SckOThMxMnaa1339LL'; // Ganti dengan secret key reCAPTCHA Anda
		$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
		$recaptcha_valid = false;
		if (!empty($recaptcha_response)) {
			$verify = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response . '&remoteip=' . $_SERVER['REMOTE_ADDR']);
			$captcha_success = json_decode($verify);
			if ($captcha_success && isset($captcha_success->success) && $captcha_success->success == true) {
				$recaptcha_valid = true;
			}
		}
		if (!$recaptcha_valid) {
			$this->session->set_flashdata('error', 'Captcha tidak valid. Silakan coba lagi.');
			redirect('admin/session');
		}
		// --- END Google reCAPTCHA validation ---

		if ($this->form_validation->run() !== FALSE)
		{
			$data = $this->input->post(NULL, TRUE);
			$employee = $this->employee_model->get_where(array('Email' => $data['Email']));
			$login_success = false;
			if(count($employee) > 0) {
				$hash = $employee[0]['Password'];
				$input_password = $data['Password'];
				$is_md5 = (strlen($hash) === 32 && ctype_xdigit($hash));
				$valid = false;
				if ($is_md5) {
					if (md5($input_password) === $hash) {
						$valid = true;
						$this->employee_model->update(['Password' => password_hash($input_password, PASSWORD_DEFAULT)], $employee[0]['ID']);
					}
				} else {
					if (password_verify($input_password, $hash)) {
						$valid = true;
					}
				}
				if ($valid && $employee[0]["Status"] == "Enabled") {
					// OTP wajib untuk admin
					$session = array(
						'admin_id' => $employee[0]['ID'],
						'admin_email' => $employee[0]['Email'],
						'admin_name' => $employee[0]['FirstName'] . ' ' . $employee[0]['LastName'],
						'is_admin_logged_in' => FALSE
					);
					$this->session->set_userdata($session);
					$this->session->set_userdata('temp_admin_id', $employee[0]['ID']);
					$this->session->set_userdata('temp_admin_email', $employee[0]['Email']);
					$this->session->unset_userdata($attempts_key);
					$this->session->unset_userdata($block_key);
					$this->send_otp_admin();
					redirect('admin/session/verify_otp');
				} else {
					$login_success = false;
				}
			}
			if (!$login_success) {
				$attempts++;
				$this->session->set_userdata($attempts_key, $attempts);
				if ($attempts >= $max_attempts) {
					$this->session->set_userdata($block_key, time() + ($block_minutes * 60));
					$this->session->set_flashdata('error', 'Terlalu banyak percobaan login gagal. Silakan coba lagi dalam ' . $block_minutes . ' menit.');
				} else {
					$this->session->set_flashdata("error", "Invalid Email or Password");
				}
				redirect("admin/session");
			}
		}
		$this->index();
	}
	public function send_otp_admin() {
		if (!$this->session->userdata('temp_admin_id')) {
			redirect('admin/session/login');
		}
		$admin_id = $this->session->userdata('temp_admin_id');
		$email = $this->session->userdata('temp_admin_email');
		$this->load->model('otp_model');
		$otp_code = $this->otp_model->generate_otp($admin_id, $email);
		$this->send_otp_email_admin($email, $otp_code);
		if ($this->input->is_ajax_request()) {
			echo json_encode(array('status' => 'success', 'message' => 'OTP sent'));
		} else {
			$this->session->set_flashdata('success', 'OTP sent');
			redirect('admin/session/verify_otp');
		}
	}

	public function verify_otp() {
		if (!$this->session->userdata('temp_admin_id')) {
			redirect('admin/session/login');
		}
		$this->load->model('otp_model');
		if ($this->input->post('otp_code')) {
			$admin_id = $this->session->userdata('temp_admin_id');
			$otp_code = $this->input->post('otp_code');
			if ($this->otp_model->verify_otp($admin_id, $otp_code)) {
				$this->session->unset_userdata('temp_admin_id');
				$this->session->unset_userdata('temp_admin_email');
				$session = array(
					'is_admin_logged_in' => TRUE
				);
				$this->session->set_userdata($session);
				// Log aktivitas login admin
				log_activity('Login Admin', $admin_id, $this->session->userdata('admin_name'));
				$this->session->set_flashdata('success', 'OTP verified successfully');
				redirect('admin');
			} else {
				$this->session->set_flashdata('error', 'Invalid OTP code');
			}
		} else {
			$this->session->set_flashdata('error', 'Invalid OTP code');
		}
		$data['page_title'] = 'OTP Verification';
		$this->load->view('admin/verify_otp', $data);
	}

	private function send_otp_email_admin($email, $otp_code) {
		$template = $this->autoresponder_model->get_where(array('Status' => 'Enabled', 'ID' => 12));
		if (isset($template) && count($template) > 0) {
			$from_name = $template[0]['FromName'];
			$from_email = $template[0]['FromEmail'];
			$to_email = $email;
			$subject = $template[0]['Subject'];
			$message = html_entity_decode($template[0]['Message']);
			$post['OTPCode'] = $otp_code;
			$post['Email'] = $email;
			$this->fsd->email_template($post, $from_email, $from_name, $to_email, $subject, $message);
			$this->fsd->sent_email($from_email, $from_name, $to_email, $subject, $message);
		}
	}
// ...existing code...
   public function forgot_password(){
		$this->load->library('form_validation');	
		$this->form_validation->set_rules('Email', 'Email', 'trim|required|valid_email|min_length[4]');
		if($this->form_validation->run() !== FALSE)
		{
			$email = $this->input->post('Email',TRUE);
			$employee = $this->employee_model->get_where(array('Email'=> $email, 'Status' => 'Enabled'));			
			if(count($employee)>0)
			{
				$token = $employee[0]['ID']."-".rand(12345,54321); 
				$this->employee_model->update(array('Token' => $token), $employee[0]['ID']);
				
				## Get Issue Email Template ##
				$data = $this->autoresponder_model->get_where(array('Status' => 'Enabled', 'ID' => 4)); // Forgot Password Token Email					
				## Send Email with Template ## 		
				if(isset($data) && count($data)>0)
				{
					$from_name = $data[0]['FromName'];
					$from_email = $data[0]['FromEmail'];
					$to_email = $data[0]['ToEmail'];
					$subject = $data[0]['Subject'];
					$message = html_entity_decode($data[0]['Message']);
					
					//Information
					$post['TOKEN_URL'] = site_url('admin/session/set_password/'.$token);
					$post['FirstName'] = $employee[0]['FirstName'];
					$post['LastName'] = $employee[0]['LastName'];
					$post['Email'] = $employee[0]['Email'];
			
					$this->fsd->email_template($post, $from_email, $from_name, $to_email, $subject, $message );
					$this->fsd->sent_email($from_email, $from_name,$to_email, $subject, $message );
					
					$this->session->set_flashdata("success","An email has been sent to your account.");
					redirect('admin/session/login');						
				}					
			}			
		}
		$this->session->set_flashdata("error", "Invalid email address.");
		redirect('admin/session/');
	}

	public function set_password($token)
	{
		if(empty($token))
			redirect('admin/session/');
		$data = $this->employee_model->get_where(array('Token'=> $token, 'Status' => 'Enabled'));
		if(count($data) > 0)
		{
			$length = 8;
			$password = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
			## Get Issue Email Template ##
			$template = $this->autoresponder_model->get_where(array('Status' => 'Enabled', 'ID' => 5)); // Forgot Password Token Email					
			## Send Email with Template ## 		
			if(isset($template) && count($template)>0)
			{
				$from_name = $template[0]['FromName'];
				$from_email = $template[0]['FromEmail'];
				$to_email = $template[0]['ToEmail'];
				$subject = $template[0]['Subject'];
				$message = html_entity_decode($template[0]['Message']);
				
				//Information
				$post['Password'] = $password;
				$post['FirstName'] = $data[0]['FirstName'];
				$post['LastName'] = $data[0]['LastName'];
				$post['Email'] = $data[0]['Email'];
		
				$this->fsd->email_template($post, $from_email, $from_name, $to_email, $subject, $message );
				$this->fsd->sent_email($from_email, $from_name,$to_email, $subject, $message );
				
				$userdata = array('Token' => NULL, 'Password' => md5($password) );
				$update = $this->employee_model->update($userdata, $data[0]['ID']);
				$this->session->set_flashdata("success", "Your new password has been sent to your email account.");
				redirect('admin/session/');						
			}				 				
		}
		$this->session->set_flashdata("error","Invalid token URL.");
		redirect('admin/session/');
	}
	
	public function logout()
	{
		// Log aktivitas logout admin
		$admin_id = $this->session->userdata('admin_id');
		$admin_name = $this->session->userdata('admin_name');
		log_activity('Logout Admin', $admin_id, $admin_name);
		$this->session->sess_destroy();
		$this->index();
	}
}