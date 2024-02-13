<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }} - Stripe Payment</title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
            display: flex;
            justify-content: center;
            align-content: center;
            height: 100vh;
            width: 100vw;
        }

        form {
            width: 30vw;
            min-width: 500px;
            align-self: center;
            box-shadow: 0px 0px 0px 0.5px rgba(50, 50, 93, 0.1),
                0px 2px 5px 0px rgba(50, 50, 93, 0.1), 0px 1px 1.5px 0px rgba(0, 0, 0, 0.07);
            border-radius: 7px;
            padding: 40px;
        }

        input,
        .InputElement {
            border-radius: 6px;
            margin-bottom: 6px;
            padding: 12px;
            border: 1px solid rgba(50, 50, 93, 0.1);
            height: 44px;
            font-size: 12px;
            width: 100%;
            background: white;
        }

        label {
            font-size: 12px;
        }

        #over img {
            margin-left: auto;
            margin-right: auto;
            display: block;
        }

        .ElementsApp,
        .ElementsApp .InputElement {
            font-size: 12px;
        }

        .hidden {
            display: none;
        }

        #card-error {
            color: rgb(105, 115, 134);
            text-align: left;
            font-size: 13px;
            line-height: 17px;
            margin-top: 12px;
        }

        #card-element {
            border-radius: 4px 4px 0 0;
            padding: 12px;
            border: 1px solid rgba(50, 50, 93, 0.1);
            height: 44px;
            width: 100%;
            background: white;
        }

        #payment-request-button {
            margin-bottom: 32px;
        }

        /* Buttons and links */
        button {
            background: #3c812f;
            color: #ffffff;
            font-family: Arial, sans-serif;
            border-radius: 0 0 4px 4px;
            border: 0;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: block;
            transition: all 0.2s ease;
            box-shadow: 0px 4px 5.5px 0px rgba(0, 0, 0, 0.07);
            width: 100%;
        }

        button:hover {
            filter: contrast(115%);
        }

        button:disabled {
            opacity: 0.5;
            cursor: default;
        }

        /* spinner/processing state, errors */
        .spinner,
        .spinner:before,
        .spinner:after {
            border-radius: 50%;
        }

        .spinner {
            color: #ffffff;
            font-size: 22px;
            text-indent: -99999px;
            margin: 0px auto;
            position: relative;
            width: 20px;
            height: 20px;
            box-shadow: inset 0 0 0 2px;
            -webkit-transform: translateZ(0);
            -ms-transform: translateZ(0);
            transform: translateZ(0);
        }

        .spinner:before,
        .spinner:after {
            position: absolute;
            content: "";
        }

        .spinner:before {
            width: 10.4px;
            height: 20.4px;
            background: #5469d4;
            border-radius: 20.4px 0 0 20.4px;
            top: -0.2px;
            left: -0.2px;
            -webkit-transform-origin: 10.4px 10.2px;
            transform-origin: 10.4px 10.2px;
            -webkit-animation: loading 2s infinite ease 1.5s;
            animation: loading 2s infinite ease 1.5s;
        }

        .spinner:after {
            width: 10.4px;
            height: 10.2px;
            background: #5469d4;
            border-radius: 0 10.2px 10.2px 0;
            top: -0.1px;
            left: 10.2px;
            -webkit-transform-origin: 0px 10.2px;
            transform-origin: 0px 10.2px;
            -webkit-animation: loading 2s infinite ease;
            animation: loading 2s infinite ease;
        }

        .form-div {
            background: #DFDFDF;
        }

        @-webkit-keyframes loading {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes loading {
            0% {
                -webkit-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @media only screen and (max-width: 600px) {
            form {
                width: 80vw;
            }
        }
    </style>
</head>

<body>

    <form id="stripe-form" class="form-horizontal form-div">

        <div class="form-body">
            <div class="text-center" id="over">
                <img src="https://user-images.githubusercontent.com/9677258/161246588-f82a1893-38d0-49ba-97dd-0643594f65a1.png" alt="Sadaqa Online">
            </div><br>
            <div class="row">
                <div class="col-sm-12">

                    <input type="hidden" id="donor_id" name="donor_id" value="{{ $donor_id }}">
                    <input type="hidden" id="email" name="email" value="{{ $email }}">
                    <input type="hidden" id="reference_id" value="{{ $reference_id }}">
                    <input type="hidden" name="_auth" id="_auth" value="{{ $data['auth'] }}">
                    <input type="hidden" name="paymentIntentData" id="paymentIntentData">

                    <div class="form-group form-group-sm">
                        <div class="col-sm-9">
                            <input type="text" name="name_on_card" id="name_on_card" maxlength="100"
                                class="name_on_card form-control required" placeholder="Name on Card">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">

                    <div class="form-group form-group-sm">
                        <div class="col-sm-9">
                            <div id="card-element">

                            </div>
                        </div>
                    </div>
                </div>
            </div><br>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group form-group-sm">
                        <div class="col-sm-9">
                            <input type="text" name="totalDonationAmount" id="totalDonationAmount" value="{{ $total }}"
                                class="form-control" placeholder="Amount" readonly>
                            <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button id="submit" class="btn btn-sm proceed">
            <div class="spinner hidden" id="spinner"></div>
            <span id="button-text">Pay now</span>
        </button>
        <p id="card-error" role="alert"></p>

    </form>

</body>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
<script>

var stripe = Stripe("{!! config('stripe.key') !!}");

// Disable the button until we have Stripe set up on the page
document.querySelector("button").disabled = true;

var purchase = {
    donor_id: document.querySelector("input[name=donor_id]").value,
    email: document.querySelector("#email").value,
    reference_id: document.querySelector("#reference_id").value,
    amount: document.querySelector("#totalDonationAmount").value,
    _token: document.querySelector("#csrf-token").value,
};

fetch("{{ route('stripe.payment') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(purchase)
    })
    .then(function(result) {
        return result.json();
    })
    .then(function(data) {
        var elements = stripe.elements();
        var style = {
            base: {
                color: "#32325d",
                fontFamily: 'Arial, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "12px",
                "::placeholder": {
                    color: "#32325d"
                }
            },
            invalid: {
                fontFamily: 'Arial, sans-serif',
                color: "#fa755a",
                iconColor: "#fa755a"
            }
        };

        var elementClasses = {
            focus: 'focus',
            empty: 'empty',
            invalid: 'invalid',
        };

        var card = elements.create("card", {
            style: style
        });

        // Stripe injects an iframe into the DOM
        card.mount("#card-element");

        card.on("change", function(event) {

            // Disable the Pay button if there are no card details in the Element
            document.querySelector("#card-error").textContent = event.error ? event.error.message : "";
            if (event.complete) {
                document.querySelector(".proceed").disabled = false;
            } else {
                document.querySelector(".proceed").disabled = true;
            }

        });

        var form = document.getElementById("stripe-form");
        form.addEventListener("submit", function(event) {
            event.preventDefault();

            var nameOnCard = document.querySelector("#name_on_card").value;

            if(nameOnCard == ''){
                document.querySelector("#card-error").textContent = 'Please enter name on your card.';
            }else{
                document.querySelector("#card-error").textContent = '';
                // Complete payment when the submit button is clicked
                payWithCard(stripe, card, data.clientSecret);
            }

        });
    });

// Calls stripe.confirmCardPayment
// If the card requires authentication Stripe shows a pop-up modal to
// prompt the user to enter authentication details without leaving your page.
var payWithCard = function(stripe, card, clientSecret) {
    loading(true);

    stripe
        .confirmCardPayment(clientSecret, {
            payment_method: {
                card: card
            }
        })
        .then(function(result) {
            if (result.error) {

                document.querySelector("#paymentIntentData").value = -1
                window.location = "{{ route('stripe.checkSuccess', [ 'id' => -1 ] ) }}";

            } else {
                var data = JSON.stringify(result);

                var reference_id = document.querySelector("#reference_id").value;
                var donor_id = document.querySelector("#donor_id").value;
                var _auth = document.querySelector("#_auth").value;

                window.location= "{{ config('app.url') }}/check-success?reference_id="+reference_id+"&_auth="+_auth+"&donor_id="+donor_id+"&id="+data;
            }

        });

};

/* ------- UI helpers ------- */

// Show a spinner on payment submission
var loading = function(isLoading) {
    if (isLoading) {
        console.log(loading);
        // Disable the button and show a spinner
        document.querySelector("button").disabled = true;
        document.querySelector("#spinner").classList.remove("hide");
        document.querySelector("#button-text").classList.add("hide");
    } else {
        document.querySelector("button").disabled = false;
        document.querySelector("#spinner").classList.add("hide");
        document.querySelector("#button-text").classList.remove("hide");
    }
};

</script>
