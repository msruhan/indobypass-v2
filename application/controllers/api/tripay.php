<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tripay extends FSD_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->model('credit_model');
		$this->load->library('tripay_lib');
    }

	public function index() {
        // Check if this is a GET request (browser access)
        if ($this->input->server('REQUEST_METHOD') === 'GET') {
            $this->show_api_info();
            return;
        }
        
        // Handle POST request (actual callback)
        $this->handle_callback();
    }

	private function show_api_info() {
        $response = [
            'status' => 'success',
            'message' => 'Tripay Payment Gateway API Endpoint',
            'description' => 'This endpoint is used for receiving payment callbacks from Tripay',
            'version' => '1.0',
            'methods' => [
                'POST' => 'Receive payment callback from Tripay'
            ],
            'documentation' => 'https://tripay.co.id/developer',
            'timestamp' => date('Y-m-d H:i:s'),
            'server_time' => time()
        ];
        
        echo json_encode($response, JSON_PRETTY_PRINT);
    }

    private function handle_callback() {
		$json = file_get_contents('php://input');
		$data = json_decode($json, true);
		
		if ($data) {
			$merchant_ref = $data['merchant_ref'];
			$status = $data['status'];
			
			// Update transaction status
			$this->db->where('merchant_ref', $merchant_ref);
			$this->db->update('tripay_transactions', [
				'status' => $status,
				'updated_at' => date('Y-m-d H:i:s')
			]);
			
			if ($status == 'PAID') {
				// Add credit to user account
				$transaction = $this->db->get_where('tripay_transactions', ['merchant_ref' => $merchant_ref])->row();
				if ($transaction) {
					$this->add_credit_to_user($transaction->member_id, $transaction->amount, $data);
				}else{
					echo json_encode(['success' => false]);
				}
			}else{
				echo json_encode(['success' => false]);
			}
		}else{
			echo json_encode(['success' => false]);
		}
	}

	public function tripay_return() {

		if ($this->input->server('REQUEST_METHOD') === 'GET' && !$this->input->get('tripay_reference')) {
            $this->show_api_info();
            return;
        }

		$reference = $this->input->get('tripay_reference');
		
		if ($reference) {
			$response = $this->tripay_lib->get_transaction_detail($reference);
			
			if ($response && $response['success']) {
				$status = $response['data']['status'];
				
				if ($status == 'PAID') {
					$this->session->set_flashdata('message', '<div class="alert alert-success">Payment successful!</div>');
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-warning">Payment '.$response['data']['status'].'</div>');
				}
			}else{
				$this->session->set_flashdata('message', '<div class="alert alert-warning">'. $response['success'] .' '. $response['message'] .'</div>');
			}
		}else{
			$this->session->set_flashdata('message', '<div class="alert alert-warning">Invalid payment reference!</div>');
		}
		redirect('member/dashboard/addfund');
	}

	private function add_credit_to_user($member_id, $amount, $data) {

        $this->db->trans_start();
        
        $result_sql = $this->db->query("SELECT `value` FROM app_settings WHERE `key` = 'idr'");
        $idr = $result_sql->row()->value;

        // insert amount
        $transaction_id = 1 + $this->credit_model->get_max_transaction_id(array('TransactionCode' => CASH_PAYMENT_RECEIVED));
        
        $ins['MemberID']          = $member_id;
        $ins['TransactionCode']   = CASH_PAYMENT_RECEIVED;
        $ins['TransactionID']     = $transaction_id;
        $ins['Description']       = "Added By Tripay - Method: " . $data['payment_method']; // Deskripsi dengan bank
        $ins['Amount']            = $amount / $idr;
        $ins['CreatedDateTime']   = date("Y-m-d H:i:s");
        $result_insert = $this->db->insert('gsm_credits', $ins);
    
        if ($result_insert && $idr) {
            $this->db->trans_complete();
            echo json_encode(['success' => true]);
        } else {
            $this->db->trans_rollback();
            echo json_encode(['success' => false]);
        }
	}
}