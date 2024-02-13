@extends('app')
@section('content')
<ol class="breadcrumb">
    <li><a href="{{ url('/')}}">Home</a></li>
    <li class="active">Reset Passwod</li>
</ol>
<div id="resetPassword" class="">
    <header class="popup-heading" style="margin: 20px 0 30px;">
        <h2>Reset Password</h2>
    </header>

    <div class="popup-inner">
        <form id="resetPassword-form" class="request-form">
            <div id="error-msg"></div>
            <div class="row">
                <div class="col col-lg-6">
                    <div class="form-group">
                        <label for="email">New Password <span class="required">*</span></label>
                        <input type="password" class="form-control" name="user_password" id="user_password"
                            placeholder="New Password">
                        <input type="hidden" name="token" id="token" value="<?php echo ($token); ?>">
                    </div>
                </div>
                <div class="col col-lg-6">
                    <div class="form-group">
                        <label for="email">Confirm Password <span class="required">*</span></label>
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                            placeholder="Confirm Password">
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-lg btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('modal')

@include('_modal.login')
@include('_modal.signup')
@include('_modal.forgetPassword')

@endsection

@section('footer_scripts')
<script>
    App.cartItemCount();
    var	reset_password_route = '{!! route('resetPassword') !!}';
    var	program_route = '{!! route('program') !!}';
    var	program_country_route = '{!! route('program_country') !!}';
    var	program_rate_route = '{!! route('program_rate') !!}';
    var login = '{!! route('login') !!}';
    var signup = '{!! route('signup') !!}';
    var resetPassword = '{!! route('resetPassword') !!}';
    var resetToken = '{!! route('resetToken') !!}';
    var add_to_cart = '{!! route('add_to_cart') !!}';
    var fetchCartData = '{!! route('fetchCartData') !!}';
    var update_quantity = '{!! route('update_quantity') !!}';
    var delete_item = '{!! route('delete_item') !!}';
</script>

<script src=" {{ URL::asset('assets/scripts/resetPassword.js?v='.time()) }} "></script>
<script src=" {{ URL::asset('assets/scripts/login.js?v='.time()) }} "></script>
@endsection
