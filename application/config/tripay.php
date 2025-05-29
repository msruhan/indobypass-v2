<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['tripay_merchant_code'] = '';
$config['tripay_api_key'] = '';
$config['tripay_private_key'] = '';
$config['tripay_base_url'] = 'https://tripay.co.id/api-sandbox'; // Use https://tripay.co.id/api for production
$config['tripay_callback_url'] = base_url('api/tripay');
$config['tripay_return_url'] = base_url('api/tripay/tripay_return');