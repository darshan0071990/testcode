    <script src="https://developer.payeezy.com/v1/payeezy.js" type="text/javascript">
        Payeezy.setApiKey('y6pWAJNyJyjGv66IsVuWnklkKUPFbb0a');
        Payeezy.setMerchantIdentifier('3176752955');
        var ApiSecret = "86fbae7030253af3cd15faef2a1f4b67353e41fb6799f576b5093ae52901e6f786fbae7030253af3cd15faef2a1f4b67353e41fb6799f576b5093ae52901e6f7";
        function submitthis(theForm)
        {
            return false;
        }
        function submitthis(theForm)
        {
            return false;
        }
        function validateFormOnSubmit(theForm) {

            return false;
        }


        var responseHandler = function(status, response) {
            var $form = $('#payment-info-form');
            if (status != 201) {
                if (response.error) {
                    var errorMessages = response.error.messages;
                    var allErrors = '';
                    for (i=0; i<errorMessages.length;i++)
                    {
                        allErrors = allErrors + errorMessages[i].description;
                    }
                    $form.find('.payment-errors').text(allErrors);
                }
                $form.find('button').prop('disabled', false);
            } else {
                var token = response.token.value;
                $form.append($('<input type="hidden" name="payeezyToken"/>').val(token));
                $form.get(0).submit();
            }
        };
    </script>

<body onsubmit="
					var $form = $(this);
					$form.find('button').prop('disabled', true);
					Payeezy.createToken(responseHandler);
					return true;
			">
<div class="sign_in">
    Payment Details
</div>
<!--<form action="{call to merchant server}" method="post" id="payment-info-form" >-->
<form action="#" class="form-horizontal" onsubmit="return validateFormOnSubmit(form);" method="post" id="payment-info-form" >
    <span class="payment-errors"></span>

        <div class="control-group">
            <select class="span4" payeezy-data="card_type">
                <option value="visa">Visa</option>
                <option value="mastercard">Master Card</option>
                <option value="American Express" >American Express</option>
                <option value="discover" >Discover</option>
            </select>
        </div>

        <div class="control-group">
            <input class="span4" type="text" payeezy-data="cardholder_name" placeholder="CardHolder Name"/>
        </div>

    <div class="control-group">
        <input type="text" class="span4" payeezy-data="cc_number"  placeholder="Card Number"/>
    </div>

    <div class="control-group">
        <input type="text" class="span4" payeezy-data="cvv_code"  placeholder="CVV Code"/>
    </div>


    <div class="control-group">

                <select class="span1" payeezy-data="exp_month">
                    <option value="0">Month</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                </select>
                <select class="span2" payeezy-data="exp_year">
                    <option value="0">Year</option>
                    <option value="15">2015</option>
                    <option value="16">2016</option>
                    <option value="17">2017</option>
                    <option value="18">2018</option>
                    <option value="18">2019</option>
                    <option value="18">2020</option>
                    <option value="18">2021</option>
                    <option value="18">2022</option>
                    <option value="18">2023</option>
                    <option value="18">2024</option>
                    <option value="18">2025</option>
                    <option value="18">2026</option>
                </select>

            </div>

            <input type="hidden" class="span4" name="merchant_ref" id="merchant_ref" value="3176752955"/>


                <input type="hidden" value="purchase" name="transaction_type">


    <div class="control-group">

                <input class="span4" type="text" name="amount" id="amount" placeholder="Amount" value="2" disabled/>
            </div>
            <button type="submit" class="btn btn-primary">Make Payment</button>

</form>
</body>