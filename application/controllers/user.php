<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends FSD_Controller 
{
	var $before_filter = array('name' => 'get_settings', 'except' => array());
	public function __construct()
	{
		parent::__construct();
		$this->load->model('member_model');
		$this->load->model('method_model');
		$this->load->model('fileservices_model');
		$this->load->model('autoresponder_model');
	   $this->load->model('otp_model');
	   $this->load->helper('log_activity_helper');
	}
	
	public function forgot_password()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			$this->form_validation->set_rules('Email', 'Email', 'required|valid_email');
			if ($this->form_validation->run() !== FALSE)
			{
				$query = $this->member_model->get_where(array('Email' => $this->input->post('Email') ));
				if( count($query) > 0)
				{
					//set token for password
					$token = array('Token' => $query[0]['ID']."-".rand(123456789, 987654321) );
					$this->member_model->update($token, $query[0]['ID']);
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
						$post['TokenUrl'] = site_url('user/set_password/'.$token['Token']);
						$post['FirstName'] = $query[0]['FirstName'];
						$post['LastName'] = $query[0]['LastName'];
						$post['Email'] = $query[0]['Email'];
				
						$this->fsd->email_template($post, $from_email, $from_name, $to_email, $subject, $message );
						$this->fsd->sent_email($from_email, $from_name,$to_email, $subject, $message );
						
						$this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible fade show" role="alert" role="danger"> '.$this->lang->line('error_success_email').'  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
						redirect('forgot_password');						
					}								   	
				}
				//record not found
				$this->session->set_flashdata("message", '<div class="alert alert-danger alert-dismissible fade show" role="alert" role="danger"> '.$this->lang->line('error_invalid_email').'  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
				redirect('forgot_password');	 		
			}
		}
		$data = array();
		$data["title"] = $this->lang->line('forgot_password_heading');
		$data["heading"] = $this->lang->line('forgot_password_heading');
		// $data['master_template'] = "user/forgetpassword";
		$this->load->view("user/forgetpassword", $data);
	}
	
	public function set_password($token)
	{
		if(empty($token))
			redirect('forgot_password');
			
		$data = $this->member_model->get_where(array('Token' => $token, 'Status' => 'Enabled' ));
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
				
				$userdata = array('Token' => NULL, 'Password' => $password );
				$update = $this->member_model->update($userdata, $data[0]['ID']);
				$this->session->set_flashdata("success", $this->lang->line('error_success_password'));
				redirect('login');						
			}				 				
		}
		$this->session->set_flashdata("fail", $this->lang->line('error_invalid_password'));
		redirect('login');
	}

	public function login()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{
			// --- Rate limit by IP ---
			$ip = $this->input->ip_address();
			$max_attempts = 5;
			$block_minutes = 15;
			$attempts_key = 'user_login_attempts_' . md5($ip);
			$block_key = 'user_login_block_' . md5($ip);
			$attempts = $this->session->userdata($attempts_key) ?: 0;
			$block_until = $this->session->userdata($block_key);
			if ($block_until && time() < $block_until) {
				$wait = ceil(($block_until - time()) / 60);
				$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Terlalu banyak percobaan login gagal. Silakan coba lagi dalam ' . $wait . ' menit. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
				redirect('login');
			}

			// Verifikasi Google reCAPTCHA
			$recaptcha_response = $this->input->post('g-recaptcha-response');
			if (empty($recaptcha_response)) {
				$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Silakan verifikasi captcha terlebih dahulu. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
				redirect('login');
			}

			$recaptcha_secret = '6LdWw7QrAAAAAM-DXXUex0SckOThMxMnaa1339LL'; // Ganti dengan secret key Anda
			$verify_url = 'https://www.google.com/recaptcha/api/siteverify';
			$verify_data = array(
				'secret' => $recaptcha_secret,
				'response' => $recaptcha_response,
				'remoteip' => $this->input->ip_address()
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $verify_url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($verify_data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$result = curl_exec($ch);
			$curl_error = curl_error($ch);
			curl_close($ch);

			if ($result === false || !empty($curl_error)) {
				$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Gagal memverifikasi captcha. Silakan coba lagi. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
				redirect('login');
			}

			$resultData = json_decode($result, true);
			if (!is_array($resultData) || !isset($resultData['success']) || $resultData['success'] !== true) {
				$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Captcha tidak valid. Silakan coba lagi. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
				redirect('login');
			}

			$this->form_validation->set_rules('Email', 'Email', 'required|valid_email');
			$this->form_validation->set_rules('Password', 'Password', 'required|min_length[5]');
			if ($this->form_validation->run() !== FALSE)
			{
				$data = $this->input->post(NULL, TRUE);
				$user = $this->member_model->get_where(array('Email' => $data['Email']));
				$login_success = false;
				if(count($user) > 0) {
					$hash = $user[0]['Password'];
					$input_password = $data['Password'];
					$is_md5 = (strlen($hash) === 32 && ctype_xdigit($hash));
					$valid = false;
					if ($is_md5) {
						// Cek password lama (MD5)
						if (md5($input_password) === $hash) {
							$valid = true;
							// Upgrade ke hash baru
							$this->member_model->update(['Password' => password_hash($input_password, PASSWORD_DEFAULT)], $user[0]['ID']);
						}
					} else {
						// Cek password hash baru
						if (password_verify($input_password, $hash)) {
							$valid = true;
						}
					}
					if ($valid) {
						$login_success = true;
						if($user[0]["Status"] == "Enabled") {
							$settings = $this->setting_model->get_all();
							foreach ($settings as $s)
								$data['key'][$s['Key']] = $s['Value'];
							if(isset($data['key']['OTP_verification']) && $data['key']['OTP_verification'] == 'Enabled') {
								$session = array(
									'MemberID' => $user[0]['ID'],
									'MemberEmail' => $user[0]['Email'],
									'MemberFirstName' => $user[0]['FirstName'],
									'MemberLastName' => $user[0]['LastName'],
									'MemberPhone' => $user[0]['Mobile'],
									'MemberCurrency' => $user[0]['Currency'],
									'IDR' => $data['key']['idr'],
									'is_member_logged_in' => FALSE
								);
								$this->session->set_userdata($session);
								$this->session->set_userdata('temp_user_id', $user[0]['ID']);
								$this->session->set_userdata('temp_user_email', $user[0]['Email']);
								$this->session->unset_userdata($attempts_key);
								$this->session->unset_userdata($block_key);
								$this->send_otp();
								redirect('user/verify_otp');
							} else {
								$session = array(
									'MemberID' => $user[0]['ID'],
									'MemberEmail' => $user[0]['Email'],
									'MemberFirstName' => $user[0]['FirstName'],
									'MemberLastName' => $user[0]['LastName'],
									'MemberPhone' => $user[0]['Mobile'],
									'MemberCurrency' => $user[0]['Currency'],
									'IDR' => $data['key']['idr'],
									'is_member_logged_in' => TRUE
								);
								$this->session->set_userdata($session);
								$this->session->unset_userdata($attempts_key);
								$this->session->unset_userdata($block_key);

								// Log member login activity
								$username = $user[0]['FirstName'] . ' ' . $user[0]['LastName'];
								log_activity('Login member', $user[0]['ID'], $username);
								
								redirect('member/dashboard');
							}
						} else {
							$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert" role="danger"> '.$this->lang->line('error_account_deactivated').'  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
							redirect('login');
						}
					} else {
						$login_success = false;
					}
				}
				if (!$login_success) {
					$attempts++;
					$this->session->set_userdata($attempts_key, $attempts);
					if ($attempts >= $max_attempts) {
						$this->session->set_userdata($block_key, time() + ($block_minutes * 60));
						$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert"> Terlalu banyak percobaan login gagal. Silakan coba lagi dalam ' . $block_minutes . ' menit. <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert" role="danger"> '.$this->lang->line('error_invalid_login').'  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
					}
					redirect('login');
				}
			}
			{
				$data = $this->input->post(NULL, TRUE);
				$user = $this->member_model->get_where(array('Email' => $data['Email']));
				if(count($user) > 0) {
					$hash = $user[0]['Password'];
					$input_password = $data['Password'];
					$is_md5 = (strlen($hash) === 32 && ctype_xdigit($hash));
					$valid = false;
					if ($is_md5) {
						// Cek password lama (MD5)
						if (md5($input_password) === $hash) {
							$valid = true;
							// Upgrade ke hash baru
							$this->member_model->update(['Password' => password_hash($input_password, PASSWORD_DEFAULT)], $user[0]['ID']);
						}
					} else {
						// Cek password hash baru
						if (password_verify($input_password, $hash)) {
							$valid = true;
						}
					}
					if ($valid) {
						if($user[0]["Status"] == "Enabled") {
							$settings = $this->setting_model->get_all();
							foreach ($settings as $s)
								$data['key'][$s['Key']] = $s['Value'];
							if(isset($data['key']['OTP_verification']) && $data['key']['OTP_verification'] == 'Enabled') {
								$session = array(
									'MemberID' => $user[0]['ID'],
									'MemberEmail' => $user[0]['Email'],
									'MemberFirstName' => $user[0]['FirstName'],
									'MemberLastName' => $user[0]['LastName'],
									'MemberPhone' => $user[0]['Mobile'],
									'MemberCurrency' => $user[0]['Currency'],
									'IDR' => $data['key']['idr'],
									'is_member_logged_in' => FALSE
								);
								$this->session->set_userdata($session);
								$this->session->set_userdata('temp_user_id', $user[0]['ID']);
								$this->session->set_userdata('temp_user_email', $user[0]['Email']);
								$this->send_otp();
								redirect('user/verify_otp');
							} else {
								$session = array(
									'MemberID' => $user[0]['ID'],
									'MemberEmail' => $user[0]['Email'],
									'MemberFirstName' => $user[0]['FirstName'],
									'MemberLastName' => $user[0]['LastName'],
									'MemberPhone' => $user[0]['Mobile'],
									'MemberCurrency' => $user[0]['Currency'],
									'IDR' => $data['key']['idr'],
									'is_member_logged_in' => TRUE
								);
								$this->session->set_userdata($session);
								redirect('member/dashboard');
							}
						} else {
							$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert" role="danger"> '.$this->lang->line('error_account_deactivated').'  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
							redirect('login');
						}
					} else {
						$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert" role="danger"> '.$this->lang->line('error_invalid_login').'  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
						redirect('login');
					}
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert" role="danger"> '.$this->lang->line('error_invalid_login').'  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
					redirect('login');
				}
				
				if(count($query) > 0)
				{
					if($query[0]["Status"] == "Enabled")
					{
						$settings = $this->setting_model->get_all();
						foreach ($settings as $s)
							$data['key'][$s['Key']] = $s['Value'];

						if($data['key']['OTP_verification'] == 'Enabled') {
							$session = array(
								'MemberID' => $query[0]['ID'],
								'MemberEmail' => $query[0]['Email'],
								'MemberFirstName' => $query[0]['FirstName'],
								'MemberLastName' => $query[0]['LastName'],
								'MemberPhone' => $query[0]['Mobile'],
								'MemberCurrency' => $query[0]['Currency'],
								'IDR' => $data['key']['idr'],
								'is_member_logged_in' => FALSE 
							);
							$this->session->set_userdata($session);
						
							// Instead of setting the full session, set temporary session
							$this->session->set_userdata('temp_user_id', $query[0]['ID']);
							$this->session->set_userdata('temp_user_email', $query[0]['Email']);
							
							// Send OTP and redirect to verification
							$this->send_otp();
							redirect('user/verify_otp');
						}else{
								$session = array(
								'MemberID' => $query[0]['ID'],
								'MemberEmail' => $query[0]['Email'],
								'MemberFirstName' => $query[0]['FirstName'],
								'MemberLastName' => $query[0]['LastName'],
								'MemberPhone' => $query[0]['Mobile'],
								'MemberCurrency' => $query[0]['Currency'],
								'IDR' => $data['key']['idr'],
								'is_member_logged_in' => TRUE 
							);
							$this->session->set_userdata($session);
							redirect('member/dashboard');
						}
					}	
					else 
					{
						$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert" role="danger"> '.$this->lang->line('error_account_deactivated').'  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
						redirect('login');
					}
				}
				else 
				{
					$this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible fade show" role="alert" role="danger"> '.$this->lang->line('error_invalid_login').'  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
					redirect('login');
				}			 
			}
		}
		
		if ($this->session->userdata('is_member_logged_in')) {
			redirect('member/dashboard');
		}

		$data = array();
		$data["title"] = "Login";
		$data["heading"] = "Login";
		// $data['master_template'] = "user/login";
		$this->load->view("user/login",$data);				
	}

	public function register()
	{
		if($this->input->server('REQUEST_METHOD') === 'POST')
		{        
			$this->form_validation->set_rules('FirstName', 'First Name', 'required|min_length[3]');
			$this->form_validation->set_rules('LastName', 'Last Name', 'required|min_length[3]');
			$this->form_validation->set_rules('Email', 'Email', 'required|valid_email|is_unique[gsm_members.Email]');
			$this->form_validation->set_rules('Password', 'Password', 'required|min_length[5]');
			$this->form_validation->set_rules('CPassword', 'Confirm Password', 'required|matches[Password]');
			## set custom validation error messages ##
			$this->form_validation->set_message('is_unique', $this->lang->line('error_email_already_registered'));            
			if ($this->form_validation->run() !== FALSE)
			{
				$data = $this->input->post(NULL, TRUE); //collect form data                
				//register data to database
				$token = rand(123456789, 987654321);
				unset($data['CPassword']);
				$data['Token'] = $token;
				$data['ReferralMemberID'] = $this->session->userdata('referral')? $this->session->userdata('referral'): NULL;
				$data['Status'] = 'Disabled';
				$data["CreatedDateTime"] = date("y-m-d H:i:s");
				$this->member_model->insert($data);

				// Catat log aktivitas pendaftaran user
				$this->load->model('catatan_aktivitas');
				$logdata = array(
					'user_id' => null,
					'username' => $data['Email'],
					'activity' => 'Registrasi akun baru',
					'ip_address' => $this->input->ip_address(),
					'created_at' => date('Y-m-d H:i:s')
				);
				$this->db->insert('activity_log', $logdata);

				## Get Issue Email Template ##
				$template = $this->autoresponder_model->get_where(array('Status' => 'Enabled', 'ID' => 1)); // Registration Email                    
				## Send Email with Template ##         
				if(isset($template) && count($template)>0)
				{
					$from_name = $template[0]['FromName'];
					$from_email = $template[0]['FromEmail'];
					$to_email = $template[0]['ToEmail'];
					$subject = $template[0]['Subject'];
					$message = html_entity_decode($template[0]['Message']);
					//Information
					$post['Password'] = $data['password'];
					$post['FirstName'] = $data['FirstName'];
					$post['LastName'] = $data['LastName'];
					$post['Email'] = $data['Email'];
					$post['Password'] = $this->input->post('Password', TRUE);
					$post['TokenUrl'] = site_url('user/verify/'.$token);
					$this->fsd->email_template($post, $from_email, $from_name, $to_email, $subject, $message );
					$this->fsd->sent_email($from_email, $from_name,$to_email, $subject, $message );
				}

				## Get Issue Email Template ##
				$template = $this->autoresponder_model->get_where(array('Status' => 'Enabled', 'ID' => 8)); // Registration notification to admin
				## Send Email with Template ##         
				if(isset($template) && count($template)>0)
				{
					$from_name = $template[0]['FromName'];
					$from_email = $template[0]['FromEmail'];
					$to_email = $template[0]['ToEmail'];
					$subject = $template[0]['Subject'];
					$message = html_entity_decode($template[0]['Message']);
					//Information
					$post['Password'] = $data['password'];
					$post['FirstName'] = $data['FirstName'];
					$post['LastName'] = $data['LastName'];
					$post['Email'] = $data['Email'];
					$post['Password'] = $this->input->post('Password', TRUE);                    
					$this->fsd->email_template($post, $from_email, $from_name, $to_email, $subject, $message );
					$this->fsd->sent_email($from_email, $from_name,$to_email, $subject, $message );
				}
				$this->session->set_flashdata("message", '<div class="alert alert-success alert-dismissible fade show" role="alert" role="danger"> '.$this->lang->line('error_verification_email').'  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
				redirect('login');                                    
			}
		}
		$referral = $this->input->get('referral', TRUE); 
		if( !empty($referral) )
		{
			$this->session->set_userdata('referral', $referral);
			$this->member_model->increase_referal_count($referral);
		}

		$data = array();
		$data["title"] = $this->lang->line('register_heading');
		$data["heading"] = $this->lang->line('register_heading');
		$data['master_template'] = "user/register";
		$this->load->view("user/register",$data);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');	
	}

	public function services_list()
	{
		$this->load->model('method_model');
		$data = array();
		$data['imei_services'] = $this->method_model->method_with_networks();
		$data["title"] = $this->lang->line('services_heading');
		$data["heading"] = $this->lang->line('services_heading');
		$data['master_template'] = "user/services_list";
		$this->load->view("user/master_template", $data);
	}

	public function verify($token)
	{
		if(empty($token))
			redirect('login');
			
		$data = $this->member_model->get_where(array('Token' => $token, 'Status' => 'Disabled' ));
		if(count($data) > 0)
		{
			$userdata = [
				'Token' => NULL, 
				'Status' => 'Enabled',
				'UpdatedDateTime' => date("y-m-d H:i:s")
			];
			$update = $this->member_model->update($userdata, $data[0]['ID']);
			$this->session->set_flashdata("success", $this->lang->line('error_success_account_verified'));
			redirect('login');
		}
		$this->session->set_flashdata("fail", $this->lang->line('error_invalid_token'));
		redirect('login');
	}

	public function unsubscribe()
	{
		$this->session->set_flashdata("success", $this->lang->line('error_unsubscribe_msg'));
		redirect('login');
	}

	public function change_currency()
	{
		$member_id = $this->session->userdata('MemberID');
		$member_currency = $this->input->post('currency', TRUE);
		$userdata = [
			'Currency' => $member_currency,
		];
		$update = $this->db->update('gsm_members', ['Currency' => $member_currency], ['ID' => $member_id]);

		// reset session currency
		$this->session->unset_userdata('MemberCurrency');

		// set session currency
		$this->session->set_userdata('MemberCurrency', $member_currency);
		echo json_encode($update);
		
	}

	public function send_otp() {
		if (!$this->session->userdata('temp_user_id')) {
			redirect('user/login');
		}
				
		$user_id = $this->session->userdata('temp_user_id');
		$email = $this->session->userdata('temp_user_email');
		
		// Generate OTP
		$otp_code = $this->otp_model->generate_otp($user_id, $email);
		
		// Send OTP email
		$this->send_otp_email($email, $otp_code);
		
		if ($this->input->is_ajax_request()) {
			echo json_encode(array('status' => 'success', 'message' => $this->lang->line('otp_sent')));
		} else {
			$this->session->set_flashdata('success', $this->lang->line('otp_sent'));
			redirect('user/verify_otp');
		}
	}

	public function verify_otp() {
		if (!$this->session->userdata('temp_user_id')) {
			redirect('user/login');
		}
			
		if ($this->input->post('otp_code')) {

			$user_id = $this->session->userdata('temp_user_id');
			$otp_code = $this->input->post('otp_code');
			
			if ($this->otp_model->verify_otp($user_id, $otp_code)) {
				// OTP verified successfully
				$this->session->unset_userdata('temp_user_id');
				$this->session->unset_userdata('temp_user_email');
				
				$session = array(
					'is_member_logged_in' => TRUE 
				);
				$this->session->set_userdata($session);	

				$this->session->set_flashdata('success', $this->lang->line('otp_success'));
				
				redirect('member/dashboard'); // or wherever you want to redirect
			} else {
				$this->session->set_flashdata('error', $this->lang->line('otp_invalid'));
			}
		}else{
				$this->session->set_flashdata('error', $this->lang->line('otp_invalid'));
		}
		
		$data['page_title'] = $this->lang->line('otp_title');
		$this->load->view('user/verify_otp', $data);
	}

	private function send_otp_email($email, $otp_code) {
		// Get OTP email template (you'll need to create this in your autoresponder)
		$template = $this->autoresponder_model->get_where(array('Status' => 'Enabled', 'ID' => 12)); // OTP email template
		
		if (isset($template) && count($template) > 0) {
			$from_name = $template[0]['FromName'];
			$from_email = $template[0]['FromEmail'];
			$to_email = $email;
			$subject = $template[0]['Subject'];
			$message = html_entity_decode($template[0]['Message']);
			
			// Replace OTP code in template
			$post['OTPCode'] = $otp_code;
			$post['Email'] = $email;
			
			$this->fsd->email_template($post, $from_email, $from_name, $to_email, $subject, $message);
			$this->fsd->sent_email($from_email, $from_name, $to_email, $subject, $message);
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */