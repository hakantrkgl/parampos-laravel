<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
        .card-display {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .credit-card {
            width: 100%;
            max-width: 350px;
            height: 200px;
            border-radius: 10px;
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            position: relative;
            margin: 20px auto;
        }

        .credit-card-number {
            font-size: 20px;
            margin-top: 40px;
            word-spacing: 5px;
        }

        .credit-card-owner,
        .credit-card-expiry {
            margin-top: 20px;
            font-size: 16px;
        }

        .credit-card-cvc {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 16px;
        }

        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Kart Bilgileri Seçimi -->
            <div class="col-md-3">
                <div class="card card-display">
                    <h4 class="card-title text-center">Test Cards</h4>
                    <div class="form-group">
                        <label for="testCards">Select a Test Card</label>
                        <select id="testCards" class="form-control">
                            <option value="">Select a test card</option>
                            <option value='{"cardOwner":"Ziraat Bankası","cardNumber":"4446763125813623","cardExpMonth":"12","cardExpYear":"26","cardCvc":"000"}'>Ziraat Bankası - 4446763125813623 - 12/26 - 000</option>
                            <option value='{"cardOwner":"Ziraat Bankası","cardNumber":"4546711234567894","cardExpMonth":"12","cardExpYear":"26","cardCvc":"000"}'>Ziraat Bankası - 4546711234567894 - 12/26 - 000</option>
                            <option value='{"cardOwner":"Akbank","cardNumber":"5571135571135575","cardExpMonth":"12","cardExpYear":"26","cardCvc":"000"}'>Akbank - 5571135571135575 - 12/26 - 000</option>
                            <option value='{"cardOwner":"Halk Bankası","cardNumber":"4531444531442283","cardExpMonth":"12","cardExpYear":"26","cardCvc":"001"}'>Halk Bankası - 4531444531442283 - 12/26 - 001</option>
                            <option value='{"cardOwner":"Halk Bankası","cardNumber":"5818775818772285","cardExpMonth":"12","cardExpYear":"26","cardCvc":"001"}'>Halk Bankası - 5818775818772285 - 12/26 - 001</option>
                            <option value='{"cardOwner":"İş Bankası","cardNumber":"4508034508034509","cardExpMonth":"12","cardExpYear":"26","cardCvc":"000"}'>İş Bankası - 4508034508034509 - 12/26 - 000</option>
                            <option value='{"cardOwner":"İş Bankası","cardNumber":"5406675406675403","cardExpMonth":"12","cardExpYear":"26","cardCvc":"000"}'>İş Bankası - 5406675406675403 - 12/26 - 000</option>
                            <option value='{"cardOwner":"DenizBank","cardNumber":"5200190006338608","cardExpMonth":"01","cardExpYear":"30","cardCvc":"410"}'>DenizBank - 5200190006338608 - 01/30 - 410</option>
                            <option value='{"cardOwner":"DenizBank","cardNumber":"5200190009721495","cardExpMonth":"01","cardExpYear":"30","cardCvc":"462"}'>DenizBank - 5200190009721495 - 01/30 - 462</option>
                            <option value='{"cardOwner":"QNB Finansbank","cardNumber":"4155650100416111","cardExpMonth":"01","cardExpYear":"50","cardCvc":"715"}'>QNB Finansbank - 4155650100416111 - 01/50 - 715</option>
                            <option value='{"cardOwner":"Yabancı Kart","cardNumber":"5163103002982563","cardExpMonth":"12","cardExpYear":"26","cardCvc":"000"}'>Yabancı Kart - 5163103002982563 - 12/26 - 000</option>
                            <option value='{"cardOwner":"Yabancı Kart","cardNumber":"5486742060635314","cardExpMonth":"12","cardExpYear":"26","cardCvc":"000"}'>Yabancı Kart - 5486742060635314 - 12/26 - 000</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cardOwner">Card Owner</label>
                        <input type="text" class="form-control" id="cardOwner" name="cardOwner" required>
                    </div>
                    <div class="form-group">
                        <label for="cardNumber">Card Number</label>
                        <input type="text" class="form-control" id="cardNumber" name="cardNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="cardExpMonth">Expiry Month</label>
                        <input type="text" class="form-control" id="cardExpMonth" name="cardExpMonth" required>
                    </div>
                    <div class="form-group">
                        <label for="cardExpYear">Expiry Year</label>
                        <input type="text" class="form-control" id="cardExpYear" name="cardExpYear" required>
                    </div>
                    <div class="form-group">
                        <label for="cardCvc">CVC</label>
                        <input type="text" class="form-control" id="cardCvc" name="cardCvc" required>
                    </div>
                </div>
            </div>

            <!-- Ödeme Bilgileri Formu -->
            <div class="col-md-6">
                <div class="card card-display">
                    <h4 class="card-title text-center">Payment Form</h4>
                    <form id="paymentForm" action="{{ route('processPayment') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="gsm">GSM</label>
                            <input type="text" class="form-control" id="gsm" name="gsm" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="text" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="form-group">
                            <label for="orderID">Order ID</label>
                            <input type="text" class="form-control" id="orderID" name="orderID" required>
                        </div>
                        <div class="form-group">
                            <label for="orderDescription">Order Description</label>
                            <input type="text" class="form-control" id="orderDescription" name="orderDescription" required>
                        </div>
                        <div class="form-group">
                            <label for="installment">Installment</label>
                            <input type="text" class="form-control" id="installment" name="installment" required>
                        </div>
                        <div class="form-group">
                            <label for="totalAmount">Total Amount</label>
                            <input type="text" class="form-control" id="totalAmount" name="totalAmount" required>
                        </div>
                        <div class="form-group">
                            <label for="securityType">Security Type</label>
                            <input type="text" class="form-control" id="securityType" name="securityType" value="3D" required>
                        </div>
                        <div class="form-group">
                            <label for="transactionId">Transaction ID</label>
                            <input type="text" class="form-control" id="transactionId" name="transactionId" required>
                        </div>
                        <div class="form-group">
                            <label for="ipAddress">IP Address</label>
                            <input type="text" class="form-control" id="ipAddress" name="ipAddress" value="217.131.106.179" required>
                        </div>
                        <div class="form-group">
                            <label for="currencyCode">Currency Code</label>
                            <input type="text" class="form-control" id="currencyCode" name="currencyCode" value="949" required>
                        </div>
                        <div class="form-group">
                            <input type="hidden" id="hiddenCardOwner" name="cardOwner">
                            <input type="hidden" id="hiddenCardNumber" name="cardNumber">
                            <input type="hidden" id="hiddenCardExpMonth" name="cardExpMonth">
                            <input type="hidden" id="hiddenCardExpYear" name="cardExpYear">
                            <input type="hidden" id="hiddenCardCvc" name="cardCvc">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Submit Payment</button>
                    </form>
                </div>
            </div>

            <!-- Kart Görüntüsü ve Sonuç Mesajı -->
            <div class="col-md-3">
                <div class="credit-card" id="creditCardDisplay">
                    <div class="credit-card-cvc" id="cardCvcDisplay">***</div>
                    <div class="credit-card-number" id="cardNumberDisplay">**** **** **** ****</div>
                    <div class="credit-card-owner" id="cardOwnerDisplay">John Doe</div>
                    <div class="credit-card-expiry" id="cardExpiryDisplay">MM/YY</div>
                </div>
                <div id="resultMessage" class="mt-3"></div>
            </div>
        </div>
    </div>

    <script>
        $('#cardOwner').on('input', function() {
            $('#cardOwnerDisplay').text($(this).val());
            $('#hiddenCardOwner').val($(this).val());
        });

        $('#cardNumber').on('input', function() {
            var cardNumber = $(this).val();
            var formattedCardNumber = cardNumber.replace(/(\d{4})/g, '$1 ').trim();
            $('#cardNumberDisplay').text(formattedCardNumber);
            $('#hiddenCardNumber').val(cardNumber);
        });

        $('#cardExpMonth, #cardExpYear').on('input', function() {
            var expMonth = $('#cardExpMonth').val();
            var expYear = $('#cardExpYear').val();
            $('#cardExpiryDisplay').text(expMonth + '/' + expYear);
            $('#hiddenCardExpMonth').val(expMonth);
            $('#hiddenCardExpYear').val(expYear);
        });

        $('#cardCvc').on('input', function() {
            $('#cardCvcDisplay').text($(this).val());
            $('#hiddenCardCvc').val($(this).val());
        });

        $('#testCards').on('change', function() {
            var selectedCard = $(this).val();
            if (selectedCard) {
                var cardData = JSON.parse(selectedCard);
                $('#cardOwner').val(cardData.cardOwner).trigger('input');
                $('#cardNumber').val(cardData.cardNumber).trigger('input');
                $('#cardExpMonth').val(cardData.cardExpMonth).trigger('input');
                $('#cardExpYear').val(cardData.cardExpYear).trigger('input');
                $('#cardCvc').val(cardData.cardCvc).trigger('input');
            }
        });

        $('#paymentForm').on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                url: '{{ route('processPayment') }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#resultMessage').html('<div class="alert alert-success">Payment successful: ' + response.success + '</div>');
                },
                error: function(response) {
                    $('#resultMessage').html('<div class="alert alert-danger">Payment failed: ' + response.responseText + '</div>');
                }
            });
        });
    </script>
</body>

</html>
