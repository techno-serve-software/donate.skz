@extends('account.index')

@section('sub_content')


<form class="profile-form" id="profile-form">
    <?php //print_r($donor); ?>
    <div id="error-msg"></div>
    <h3 class="">Your personal details</h3>
    <div class="row">
         
        <div class="col col-md-6 col-lg-4">
            <div class="form-group">
                <label for="title">Title <span class="required">*</span></label>
                <select id="title" name="title" class="form-control
                        selectpicker">
                    <option value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Ms">Ms</option>
                    <option value="Miss">Miss</option>
                </select>
            </div>

        </div>
        <div class="col col-md-6 col-lg-4">
            <div class="form-group">
                <label for="first-name">First
                    name <span class="required">*</span></label>
                <input type="text" name="first_name" id="first_name" value="{{$donor->first_name}}"
                    class="form-control txtfirstcapitaleachword" placeholder="eg. John">
                <input type="hidden" name="donor_id" value="{{$donor->donor_id}}">
             
            </div>

        </div>
        <div class="col col-md-6 col-lg-4">
            <div class="form-group">
                <label for="last-name">Last name
                    <span class="required">*</span></label>
                <input type="text" name="last_name" id="last_name" value="{{$donor->last_name}}"
                    class="form-control txtfirstcapitaleachword" placeholder="eg. Doe">
            </div>
        </div>
    </div>

   

    <hr>
    <h3 class="">Your contact details</h3>
    <div class="row">
        <div class="col col-md-6 col-lg-4">
            <div class="form-group">
                <label for="phone">Phone <span class="required">*</span></label>
                <input type="text" name="phone" id="phone" value="{{$donor->mobile ?? ''}}"
                    class="form-control allow-only-number">
            </div>
        </div>
        <div class="col col-md-6 col-lg-4">
            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="text" name="email" {{$donor->email !=''?'readonly':''}} id="email"
                    value="{{$donor->email}}" class="form-control" placeholder="john@example.com">
            </div>
        </div>
    </div>

    {{-- <hr>
    <h3 class="">Change Password</h3>
    <div class="row">
        <div class="col col-md-6 col-lg-4">
            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" value="" class="form-control" autocomplete="false">
            </div>
        </div>
        <div class="col col-md-6 col-lg-4">
            <div class="form-group">
                <label for="confirm-password">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" value="" class="form-control">
            </div>
        </div>
    </div> --}}


    <div class="text-center">
        <button type="submit" class="btn btn-lg btn-primary" style="background-color:#0f4f7b">Update your information</button>
    </div>
</form>
@endsection





@section('modal')

{{-- @include('_modal.address') --}}
@include('_modal.pafdata')
@endsection

@section('footer_scripts')
<script>
    App.cartItemCount();
    App.textUpper();
    App.textFirstCapEachWord();
    App.textFirstCap();
    App.allowNumber();
    
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
    var city_route    = '{!! route('city') !!}';
    var get_paf_data  = '{!! route('get_paf_data') !!}';
    var city_name_route    = '{!! route('city_name') !!}';

</script>

<script src=" {{ URL::asset('assets/scripts/profile.js')}} "></script>
<script src=" {{ 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js' }} "></script>
@endsection