<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function format_currency($amount) {
    // Load the Session library
    $CI =& get_instance();
    $sess_IDR = $CI->session->userdata('IDR');
    if (!$sess_IDR) $sess_IDR = 1;
    $sess_currency = $CI->session->userdata('MemberCurrency');

    // Define the currencies
    $currencies = array(
        'USD' => array('symbol' => '$ ', 'decimals' => 2),
        'IDR' => array('symbol' => 'Rp ', 'decimals' => 2, 'thousand_separator' => '.'),
        // add more currencies as needed
    );

    // Default to IDR if not set or not valid
    if (!$sess_currency || !isset($currencies[$sess_currency])) {
        $sess_currency = 'IDR';
    }

    $currency_info = $currencies[$sess_currency];
    $amount_count = $sess_currency == 'USD' ? $amount : $amount * $sess_IDR;
    $thousand_separator = isset($currency_info['thousand_separator']) ? $currency_info['thousand_separator'] : ',';
    $formatted_amount = number_format($amount_count, $currency_info['decimals'], ',', $thousand_separator);
    return $currency_info['symbol'] . $formatted_amount;
}