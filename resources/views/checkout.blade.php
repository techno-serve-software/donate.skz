@extends('app')

@section('header_scripts')
<link href='https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css' rel='stylesheet' type='text/css'>

<style type="text/css">
    .search-form button {
        position: absolute;
        right: 0;
        top: 68%;
        width: 60px;
        height: 43px;
        background: none;
        border: none;
        color: #0f4f7b;
        cursor: pointer;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
    }
    .alert-success {
    background-color: #0f4f7b;
    border-color: #ebccd1;
    color: beige;
  }
</style>
@endsection
@section('content')
<ol class="breadcrumb">
    <li><a href="{{ url('/')}}">Home</a></li>
    <li class="active">Checkout</li>
</ol>
<header class="page-header">
    <h1 class="text-warning">Checkout</h1>
</header>
<!-- <div class="form-message"></div> -->
<form class="checkout-form" id="checkout-form" method="POST" action="{{ action('CheckoutController@donate') }}">
    {{ csrf_field() }}


    <h2 class="">Your donations summary</h2>
    <a class="btn btn-primary btn-xs pull-right-" href="{{ route('home') . '#cart-section'  }}"> <i class="fa fa-pencil"></i> Edit Cart</a>
    <br>
    <br>
    <?php $cart_data = \Session::get('cart'); ?>
    <?php $one_off_total = $monthly_total = 0; ?>
    @if (in_array('one-off', array_column($cart_data, 'donation_period')))
    <table class="table">
        <thead>
            <tr class="active">
                <th width="80%">One-off Donation Detail</th>
                <th>Donation Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart_data as $cart)
            @if ($cart->donation_period == 'one-off' )
            <?php $one_off_total += $cart->donation_amount * $cart->quantity; ?>
            <tr>
                <td>{{ $cart->category_name . ' - '. $cart->program_name . ' ( ' . $cart->country_name . ' ) ' }} <span
                        class="text-primary">&cross;</span> {{ $cart->quantity }}</td>
                <td><i class="fa fa-gbp"></i> {{ number_format($cart->donation_amount * $cart->quantity, 2) }}</td>
            </tr>
            @endif
            @endforeach

            <tr>
                <th>Total</th>
                <td><i class="fa fa-gbp"></i> {{ number_format($one_off_total, 2) }}</td>
            </tr>
        </tbody>
    </table>
    <input type="hidden" name="total_amount" value="{{ number_format($one_off_total, 2) }}">
    @endif

    @if (in_array('direct-debit', array_column($cart_data, 'donation_period')))
    <table class="table">
        <thead>
            <tr class="active">
                <th width="80%">Monthly Donation Detail</th>
                <th>Donation Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart_data as $cart)
            @if ($cart->donation_period == 'direct-debit' )
            <?php $monthly_total += $cart->donation_amount * $cart->quantity; ?>
            <tr>
                <td>{{ $cart->category_name . ' - '. $cart->program_name . ' ( ' . $cart->country_name . ' ) ' }} <span
                        class="text-primary">&cross;</span> {{ $cart->quantity }}</td>
                <td><i class="fa fa-gbp"></i> {{ number_format($cart->donation_amount * $cart->quantity, 2) }}</td>
            </tr>
            @endif
            @endforeach

            <tr>
                <th>Total</th>
                <td><i class="fa fa-gbp"></i> {{ number_format($monthly_total, 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    @if (\Session::has('user'))

    @include('checkout._form')

    @else

    {{-- <div class="text-center user-login-btns animated fadeInUpSmall">
        <p class="lead">
            You are currently not logged into Sadaqa Online. You can, if one so wishes, donate to Sadaqa Online without registering an account, however you won't be able to track all the donations that you pledge to us. Registration is easy, and can be done in seconds.
        </p>
        <br>
        <a href="#signup-form" class="btn btn-lg btn-default btn-popup">Signup</a>
        <a href="#login-form" class="btn btn-lg btn-primary btn-popup">Login</a>
        &emsp; OR
        <a class="btn btn-lg btn-secondary guest-login">Continue as Guest</a>

    </div> --}}

    @include('checkout._guest_form')

    @endif


</form>
@endsection

@section('modal')

{{-- @include('_modal.address') --}}
@include('_modal.login')
@include('_modal.signup')
@include('_modal.forgetPassword')
@include('_modal.pafdata')

@endsection

@section('footer_scripts')
<script>
    App.cartItemCount();
    App.textUpper();
    App.textFirstCapEachWord();
    App.textFirstCap();
    App.allowNumber();

    $("#sortcode").mask("99-99-99");
    $("#account_number").mask("9999 9999");

    var	donate = '{!! route('donate') !!}';

    var login = '{!! route('login') !!}';
    var signup = '{!! route('signup') !!}';
    var resetPassword = '{!! route('resetPassword') !!}';
    var resetToken = '{!! route('resetToken') !!}';
    var fetchCartData = '{!! route('fetchCartData') !!}';
    var addNewAddress = '{!! route('addNewAddress') !!}';
    var city_route    = '{!! route('city') !!}';
    var city_name_route    = '{!! route('city_name') !!}';
    var get_paf_data  = '{!! route('get_paf_data') !!}';

</script>

<script src=" {{ URL::asset('assets/scripts/checkout.js?v='.time()) }} "></script>
<script src=" {{ URL::asset('assets/scripts/login.js?v='.time()) }} "></script>
<script src=" {{ URL::asset('assets/scripts/bootbox.min.js?v='.time()) }} "></script>
<script src=" {{ 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js' }} "></script>
<!-- Stripe JavaScript library -->
<script src="https://js.stripe.com/v3/"></script>

<script>
    $(document).ready(function(){

        // Set Stripe publishable key to initialize Stripe.js
        const stripe = Stripe('{{ config("stripe.key") }}');

        // stripe checkout function
        stripeCheckout = function(donor_session, ref) {
            setLoading(true);

            createCheckoutSession(donor_session, ref).then(function(data) {
                if (data.sessionId) {
                    stripe.redirectToCheckout({
                        sessionId: data.sessionId,
                    }).then(handleResult);
                } else {
                    handleResult(data);
                }
            });
        }

        // Create a Checkout Session with the selected product
        const createCheckoutSession = function(_id, _ref) {

            return fetch('{{ route("stripe.init") }}', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    createCheckoutSession: 1,
                    donor_session: _id,
                    reference_id: _ref,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }),
            }).then(function(result) {
                return result.json();
            });
        };

        // Handle any errors returned from Checkout
        const handleResult = function(result) {
            if (result.error) {
                showMessage(result.error.message);
            }

            setLoading(false);
        };

        // Show a spinner on payment processing
        setLoading = function(isLoading) {

            if (isLoading) {
                //  show a spinner
                var dialog = bootbox.dialog({
                    message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Please wait while we redirect you to the payment gateway...</p>',
                    closeButton: false
                });

            } else {
                //hide the spinner
                // swal.close();
            }
        }

        // Display message
        function showMessage(messageText) {
            const messageContainer = document.querySelector("#paymentResponse");

            messageContainer.classList.remove("hidden");
            messageContainer.textContent = messageText;

            setTimeout(function() {
                messageContainer.classList.add("hidden");
                messageText.textContent = "";
            }, 5000);
        }
    })
</script>
@endsection
