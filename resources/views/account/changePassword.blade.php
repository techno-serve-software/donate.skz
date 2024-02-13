@extends('account.index')

@section('sub_content')
    <form class="changepassword-form" id="changepassword-form">
        <div id="error-msg"></div>
        <div class="row" style="margin-left:15px">
            <div class="form-group">
                <label for="new-password">New Password
                    <span class="required">*</span></label><br />
                <input type="password" id="new_password" name="new_password" placeholder="New Password" class="form-control"
                    style="border: 1px solid;">
            </div>
        </div>
        <br />
        <div class="row" style="margin-left:15px">
            <div class="form-group">
                <span><b>Password length:</b><br />Use at least 8 characters. Don't use a password from another site, or
                    something too obvious like your pet's name.</span><br />
                <label for="new-password">Confirm Password
                    <span class="required">*</span></label><br />
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" class="form-control"
                    style="border: 1px solid;" />
            </div>
        </div><br />
        {{-- <div class="error_message"></div>
        <div class="alert alert-danger pass-wrong">
            Password didn't match !!!
        </div>
        <div class="alert alert-success pass-correct">
            Password match, Please submit !!!
        </div> --}}
        <div class="text-center" style="margin-left:15px">
            <a href="{!! route('login') !!}"><button type="submit" class="btn btn-lg btn-primary" id="change_password" style="background-color:#0f4f7b">Change Password</button></a>
        </div>
    </form>
@endsection

@section('footer_scripts')
    <script>
        App.cartItemCount();
        App.textUpper();
        App.textFirstCapEachWord();
        App.textFirstCap();
        App.allowNumber();

        var program_route = '{!! route('program') !!}';
        var program_country_route = '{!! route('program_country') !!}';
        var program_rate_route = '{!! route('program_rate') !!}';
        var login = '{!! route('login') !!}';
        var signup = '{!! route('signup') !!}';
        var resetPassword = '{!! route('resetPassword') !!}';
        var resetToken = '{!! route('resetToken') !!}';
        var add_to_cart = '{!! route('add_to_cart') !!}';

        var fetchCartData = '{!! route('fetchCartData') !!}';
        var update_quantity = '{!! route('update_quantity') !!}';
        var delete_item = '{!! route('delete_item') !!}';
        var city_route = '{!! route('city') !!}';
        var get_paf_data = '{!! route('get_paf_data') !!}';
        var city_name_route = '{!! route('city_name') !!}';
    </script>

    <script src=" {{ URL::asset('assets/scripts/changepassword.js') }} "></script>
    <script src=" {{ 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js' }} "></script>
@endsection
