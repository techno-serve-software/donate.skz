@extends('app')

@section('content')
<div class="donate-form-section" id="donate-form">
    <header class="page-header">
        <h1 class="text-warning">Book Your Qurbani Now</h1>
    </header>

    <form class="donation-form" id="donation-form">
        <div class="row">
            <div class="colg">
                <div class="magic-box bg-light clearfix">
                    <h3 class="text-white"> <i class="fa fa-info-circle"></i> Step to follow: </h3>
                    <p class="lead">
                        Step 1 : Select the Animal Type. <br>
                        Step 2 : <strong>ADD QURBANI NAME</strong> for each Share. <br>
                        Step 3 : If you want to give 2 or more than 2 Qurbanis Hit <strong>ADD NEW QURBANI NAME</strong> button. <br>
                        Step 4 : If you are giving Qurbani for both type of Animals (Cow/Bull Share & Sheep/Goat) then
                        finish adding 1 type of Qurbani by Hitting <strong>ADD QURBANI</strong> and then start with the other type of
                        animal. <br>
                        Step 5 : Repeat the same process. <br>
                        Step 6 : Check your Donation Cart below to Proceed to Payment or add more donation. <br>
                    </p>
                </div>
            </div>
        </div>
        <input type="hidden" name="payment_method" id='payment_method' value="O">
        <input type="hidden" name="donation_type" id='donation_type' value="5"> {{-- Qurbani --}}

        <?php if(isset($_REQUEST['source']) && $_REQUEST['source'] == 'home'): ?>
            <input type="hidden" id="x_source" value="<?php echo $_REQUEST['source'] ?>">
            <input type="hidden" id="x_animal" value="<?php if(isset($_REQUEST['animal'])) echo $_REQUEST['animal']; ?>">
            <input type="hidden" id="x_shares" value="<?php if(isset($_REQUEST['shares'])) echo $_REQUEST['shares']; ?>">
        <?php endif; ?>

        <div id="donation-type-group" class="form-group">
            <label for="country">Country <span class="required">*</span></label>

            <select id="country" name="country" class="form-control selectpicker">
                @forelse ($countries->country as $country)
                <option value=" {{$country->country_id}} ">{{$country->country_name}}</option>
                @empty
                <option>No Country</option>
                @endforelse
            </select>

        </div>

        <div id="program-group" class="form-group">
            {{-- Program input goes here --}}
        </div>


        <div id="participant-group" class="form-group" data-count="1">

        </div>

        <div class="row">
            <div class="col col-md-3 col-xs-4">
                <button type="button" class="btn btn-sm btn-default">Total : <i class="fa fa-gbp"></i> <span
                        id="total-amount">0</span> </button>
                <input type="hidden" name="camount" id="camount" value="0">
                <input type="hidden" name="custom_amount" value="">
            </div>
            <div class="col col-md-6 col-xs-6">
                <button type="button" class="btn btn-sm btn-danger add-participant">Add New Qurbani Name</button>
            </div>
        </div>
        <hr>
        <div class="text-center">
            <button type="submit" class="btn btn-lg btn-primary">Add Qurbani</button>
        </div>
    </form>
</div>
<hr>
@include('qurbani-cart')
@endsection

@section('modal')

@include('_modal.login')
@include('_modal.signup')
@include('_modal.forgetPassword')

@endsection

@section('footer_scripts')
<script>
    App.allowNumber();
    App.cartItemCount();



    var	program_route = '{!! route('program') !!}';
    var	program_country_route = '{!! route('program_country') !!}';
    var	program_rate_route = '{!! route('program_rate') !!}';
    var qurbani_program_rate_route = '{!! route('fetchQurbaniPrograms') !!}';
    var login = '{!! route('login') !!}';
    var signup = '{!! route('signup') !!}';
    var resetPassword = '{!! route('resetPassword') !!}';
    var resetToken = '{!! route('resetToken') !!}';
    var add_to_cart = '{!! route('add_to_cart') !!}';
    var fetchCartData = '{!! route('fetchCartData') !!}';
    var update_quantity = '{!! route('update_quantity') !!}';
    var delete_item = '{!! route('delete_item') !!}';
</script>

<script src=" {{ URL::asset('assets/scripts/qurbani.js?v='.time()) }} "></script>
<script src=" {{ URL::asset('assets/scripts/login.js?v='.time()) }} "></script>
<script>
    $(document).ready(function () {
   $('#country').trigger('change');
});
</script>
@endsection
