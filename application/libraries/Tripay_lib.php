<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tripay_lib {
    
    private $CI;
    private $merchant_code;
    private $api_key;
    private $private_key;
    private $base_url;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->config('tripay');
        
        $this->merchant_code = $this->CI->config->item('tripay_merchant_code');
        $this->api_key = $this->CI->config->item('tripay_api_key');
        $this->private_key = $this->CI->config->item('tripay_private_key');
        $this->base_url = $this->CI->config->item('tripay_base_url');
    }
    
    public function get_payment_channels() {
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => $this->base_url . '/merchant/payment-channel',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->api_key
            ],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ));
        
        $response = curl_exec($curl);
        $error = curl_error($curl);
        
        curl_close($curl);
        
        if ($error) {
            return false;
        }
        
        return json_decode($response, true);
    }
    
    public function create_transaction($data) {
        $amount = $data['amount'];
        $method = $data['method'];
        $merchant_ref = $data['merchant_ref'];
        $customer_name = $data['customer_name'];
        $customer_email = $data['customer_email'];
        $customer_phone = $data['customer_phone'];
        $order_items = $data['order_items'];
        $callback_url = $this->CI->config->item('tripay_callback_url');
        $return_url = $this->CI->config->item('tripay_return_url');
        $expired_time = (time() + (24 * 60 * 60)); // 24 hours
        
        $signature = hash_hmac('sha256', $this->merchant_code . $merchant_ref . $amount, $this->private_key);
        
        $payload = [
            'method'         => $method,
            'merchant_ref'   => $merchant_ref,
            'amount'         => $amount,
            'customer_name'  => $customer_name,
            'customer_email' => $customer_email,
            'customer_phone' => $customer_phone,
            'order_items'    => $order_items,
            'callback_url'   => $callback_url,
            'return_url'     => $return_url,
            'expired_time'   => $expired_time,
            'signature'      => $signature
        ];
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => $this->base_url . '/transaction/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->api_key,
                'Content-Type: application/json'
            ],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_POST           => true,
        ));
        
        $response = curl_exec($curl);
        $error = curl_error($curl);
        
        curl_close($curl);
        
        if ($error) {
            return false;
        }
        
        return json_decode($response, true);
    }
    
    public function get_transaction_detail($reference) {
        $signature = hash_hmac('sha256', $this->merchant_code . $reference, $this->private_key);
        
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
            CURLOPT_FRESH_CONNECT  => true,
            CURLOPT_URL            => $this->base_url . '/transaction/detail?' . http_build_query([
                'reference' => $reference,
                'signature' => $signature
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $this->api_key
            ],
            CURLOPT_FAILONERROR    => false,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4
        ));
        
        $response = curl_exec($curl);
        $error = curl_error($curl);
        
        curl_close($curl);
        
        if ($error) {
            return false;
        }
        
        return json_decode($response, true);
    }
}