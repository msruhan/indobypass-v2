<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<div class="page-header">
    <div class="d-flex justify-content-between">
        <h3 class="fw-bold"><i class="fas fa-credit-card me-3"></i>Add Fund</h3>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Add Fund</a>
            </li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
        <?= $this->session->flashdata('message') ?>
        <?= form_error('Credit', '<div class="alert alert-danger alert-dismissible fade show" role="alert">', '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'); ?>
        <div class="card">
            <div class="card-header">
                <div class="card-title"><?php echo $this->lang->line('credit_fields_add_credit'); ?></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php echo form_open('member/checkout', array('role' => 'form', 'method' => 'post','id' => 'imeireq' ,'name' => 'form2', 'class' => 'form-horizontal', 'onsubmit' => 'openLoading()')); ?>
                    <div class="form-group">
                        <label
                            class="control-label"><?php echo $this->lang->line('credit_fields_payment_type'); ?></label>
                        <div class="col-8">
                            <select name="payment_type" id="payment_type" class="form-control" required onchange="changePaymentType()">
                                <!-- <option value="midtrans">Indonesia Payment</option> -->
                                <option value="">--Select payment type--</option>
                                <option value="tripay">Bank Indonesia (Payment Gateway)</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group credit_paypal">
                        <label
                            class="control-label"><?php echo $this->lang->line('credit_fields_no_of_credits'); ?></label>
                        <div class="col-8">
                            <input type="number" min="20" step="0.1" name="Credit"
                                placeholder="<?php echo $this->lang->line('credit_fields_no_of_credits'); ?>" required
                                class="form-control">
                        </div>
                    </div>
                    <div class="form-group credit_midtrans">
                        <label
                            class="control-label">Credit Amount (IDR)</label>
                        <div class="col-8">
                            <input type="text" class="form-control input_amount" id="inputCredit" placeholder="0">
                        </div>
                    </div>
                    <div class="form-group credit_tripay">
                        <label
                            class="control-label">Credit Amount (IDR)</label>
                        <div class="col-8">
                            <input type="text" class="form-control input_amount" id="inputCreditTripay" placeholder="0" required>
                        </div>
                    </div>
                    <div class="form-group credit_tripay">
                        <label class="control-label">Payment Method</label>
                        <div class="col-8">
                            <select name="tripay_method" id="tripay_method" class="form-control" required>
                            </select>
                        </div>
                    </div>
                    <div class="form-group btn_paypal">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit"
                                class="btn btn-primary btn-sm"><?php echo $this->lang->line('credit_fields_add_credit'); ?></button>
                        </div>
                    </div>
                    <div class="form-group btn_midtrans">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit"
                                class="btn btn-info btn-sm" onclick="getTokenMidtrans()">Add Credits</button>
                        </div>
                    </div>
                    <div class="form-group btn_tripay">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit"
                                class="btn btn-info btn-sm" onclick="processTripayPayment()">Add Credits</button>
                        </div>
                    </div>
                     <div class="form-grou credit_tripay">
                        <div class="col-12">
                            <div class="alert alert-info mb-2" style="font-size:0.95rem;">
                                Dengan memilih metode ini, verifikasi transaksi dan penambahan saldo akan diproses otomatis dalam estimasi 5–10 menit.
                            </div>
                        </div>
                    </div>

                    <div class="form-group note_paypal">
                        <div class="col-sm-offset-3 col-sm-9">
                            <span style="color:red;"> Note:- <?php echo $paypal_settings[0]['percent'].'%' ?> will be
                                charged.br </span><br>
                            <span style="color:red;"> PAYPAL ACCEPT </span>
                        </div>
                    </div>
                    <?php echo form_close(); ?>
                </div>

                <div class="row mt-3">
                    <div class="column">
                        <p6 style="color:;">

                            <div id='gcw_mainFB0OlsZi4' class='gcw_mainFB0OlsZi4'></div>
                            <a id='gcw_siteFB0OlsZi4' href='https://freecurrencyrates.com/en/'>FreeCurrencyRates.com</a>
                            <script>
                            function reloadFB0OlsZi4() {
                                var sc = document.getElementById('scFB0OlsZi4');
                                if (sc) sc.parentNode.removeChild(sc);
                                sc = document.createElement('script');
                                sc.type = 'text/javascript';
                                sc.charset = 'UTF-8';
                                sc.async = true;
                                sc.id = 'scFB0OlsZi4';
                                sc.src =
                                    'https://freecurrencyrates.com/en/widget-vertical-editable?iso=USD-EUR-GBP-JPY-CNY-XUL-INR&df=1&p=FB0OlsZi4&v=fits&source=yahoo&width=245&width_title=0&firstrowvalue=1&thm=A6C9E2,FCFDFD,4297D7,5C9CCC,FFFFFF,C5DBEC,FCFDFD,2E6E9E,000000&title=Currency%20Converter&tzo=-330';
                                var div = document.getElementById('gcw_mainFB0OlsZi4');
                                div.parentNode.insertBefore(sc, div);
                            }
                            reloadFB0OlsZi4();
                            </script>
                            <!-- put custom styles here: .gcw_mainFB0OlsZi4{}, .gcw_headerFB0OlsZi4{}, .gcw_ratesFB0OlsZi4{}, .gcw_sourceFB0OlsZi4{} -->

                        </p6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Payment Details</div>
            </div>
            <div class="card-body">
                <div class="row">

                    <div id="payee_account" class="form-bg1 recevier-det">
                        <p> We Accept More Payments </p>

                        <ul>
                            <li class="mt-2">Owner Name : <b>Masruhan Khotib</b> </li>
                            <li class="mt-2">Binance ID : <b>82014648</b></li>
                            <li class="mt-2">Binance Email Address : msrhn.khotib@gmail.com</b> </li>
                            <li class="mt-2">BEP (BEP20) : 0xa0af7b7fc35354f3b8d666ce0cee9b91e8543b73</b> </li>
                            <li class="mt-2">VISA / MASTERCARD ACCEPT : <b>SEND YOUR FULL NAME , EMAIL , COUNTRY
                                    <a href="https://wa.me/6285158856462"><br>
                                        <i class="fab fa-whatsapp"></i>WhatsApp</b>
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
#gcw_siteFB0OlsZi4 {
    font-size: x-small;
    text-align: center;
    width: 245px;
    display: block;
    margin: auto;
}
</style>
<script type="text/javascript">
var base_url = "<?= base_url() ?>";
$(document).ready(function() {

    loading_processing();
    openLoading = function() {
        loadingPannel.show();
    }

    $('.credit_paypal').addClass('d-none', true);
    $('.btn_paypal').addClass('d-none', true);
    $('.note_paypal').addClass('d-none', true);

    $('.credit_tripay').addClass('d-none', true);
    $('#inputCredit').attr('disabled', true);
    
    $('.btn_tripay').addClass('d-none', true);

    getPaymentChannel();
});

function changePaymentType () {

    if($('#payment_type').val() == 'midtrans'){
        $('.credit_paypal').addClass('d-none', true);
        $('.btn_paypal').addClass('d-none', true);
        $('.note_paypal').addClass('d-none', true);

        $('.credit_midtrans').removeClass('d-none', true);
        $('.btn_midtrans').removeClass('d-none', true);

        $('.credit_tripay').addClass('d-none', true);
        $('.btn_tripay').addClass('d-none', true);

    } if($('#payment_type').val() == 'tripay') {
        $('.credit_paypal').addClass('d-none', true);
        $('.btn_paypal').addClass('d-none', true);
        $('.note_paypal').addClass('d-none', true);

        $('.credit_tripay').removeClass('d-none', true);
        $('.btn_tripay').removeClass('d-none', true);

        $('.credit_midtrans').addClass('d-none', true);
        $('.btn_midtrans').addClass('d-none', true);
    } else{
        $('.credit_paypal').removeClass('d-none', true);
        $('.btn_paypal').removeClass('d-none', true);
        $('.note_paypal').removeClass('d-none', true);

        $('.credit_midtrans').addClass('d-none', true);
        $('.btn_midtrans').addClass('d-none', true);

        $('.credit_tripay').addClass('d-none', true);
        $('.btn_tripay').addClass('d-none', true);

    }
}

function getTokenMidtrans(){

    if($('#inputCredit').val() == 0){
        swal('Warning!', 'Please select credit', 'warning');
        return false;
    }

    $.ajax({
    method: "POST",
    dataType: "JSON",
    url: base_url + "member/checkout/getTokenMidtrans",
    data: {
        'gross_amount': $('#inputCredit').val(),
    },
    beforeSend: function () { },
    success: function (token) {

        window.snap.pay(token, {
            onSuccess: function(result){
                /* You may add your own implementation here */
                // alert("payment success!"); console.log(result);
                swal('Success!', 'payment success!', 'success');
                location.reload();
            },
            onPending: function(result){
                /* You may add your own implementation here */
                // alert("wating your payment!"); console.log(result);
                swal('Warning!', 'wating your payment!', 'warning');
            },
            onError: function(result){
                /* You may add your own implementation here */
                // alert("payment failed!"); console.log(result);
                swal('Error!', 'payment failed!', 'error');
            },
            onClose: function(){
                /* You may add your own implementation here */
                // alert('you closed the popup without finishing the payment');
                swal('Warning!', 'you closed the popup without finishing the payment', 'warning');
            }
        })
        
    },
    })
    .done(function (data) { })
    .fail(function (jqXHR, textStatus, errorThrown) {
        console.log("Error!!! " + errorThrown);
    });
}

function processTripayPayment() {

    if($('#inputCreditTripay').val() == 0){
        swal('Warning!', 'Please enter credit amount', 'warning');
        return false;
    }

    if($('#tripay_method').val() == ''){
        swal('Warning!', 'Please select payment method', 'warning');
        return false;
    }

    $.ajax({
        method: "POST",
        dataType: "JSON",
        url: base_url + "member/checkout/getTokenTripay",
        data: {
            'gross_amount': $('#inputCreditTripay').val(),
            'payment_method': $('#tripay_method').val()
        },
        beforeSend: function () { },
        success: function (response) {
            if(response.success) {
                window.open(response.checkout_url, '_blank');
            } else {
                swal('Error!', response.message, 'error');
            }
        },
        error: function() {
            swal('Error!', 'Something went wrong!', 'error');
        }
    });
}

function getPaymentChannel() {
    $.ajax({
        method: "POST",
        dataType: "JSON",
        url: base_url + "member/checkout/getPaymentChannel",
        data: {},
        beforeSend: function () { },
        success: function (response) {

            var html = '<option value="">--Select a method--</option>';
    
            $.each(response.data, function(index, method) {
                if (method) {
                     html += '<option value="' + method.code + '">' +method.name + '</option>';
                }
            });
            
            $('#tripay_method').html(html);
        },
        error: function() {
            swal('Error!', 'Something went wrong!', 'error');
        }
    });
}

$('.input_amount').on('input', function () {
    // var value = $(this).val();
    $(this).val(function (index, value) {
        return value.replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    });
});
</script>