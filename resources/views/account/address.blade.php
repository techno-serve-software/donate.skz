@extends('account.index')
@section('header_scripts')
<link href='https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css' rel='stylesheet' type='text/css'>

<style type="text/css">
    .search-form {
        position: absolute;
        right: 0;
        top: 25%;
        width: 95px;
        height: 43px;
        background: none;
        border: none;
        color: #0f4f7b;
        cursor: pointer;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
    }
    .address-guide {
    background-color: #0f4f7b;
    border-color: #ebccd1;
    color: beige;
    }
</style>
@endsection
@section('sub_content')


<form class="profile-form" id="profile-form">
    <?php //print_r($address); ?>
    <div id="error-msg"></div>

    <h3 class="">Your address</h3>

    <div class="row">
        <div class="col col-md-4">
            <div class="magic-box bg-light">
                <?php $active = $address->active ?? 'N' ?>

                <span><i class="fa fa-check "></i> {{ $active=='Y' ? 'Active':'No address registered yet.' }}</span>
                <br>
                <br>
                <p>{{ $address->address1 ?? '' }} {{ $active=='Y' ? ',':''}} {{ $address->address2 ?? ''  }}
                <br>{{ $address->city_name ?? '' }}
                <br>{{ $address->post_code ?? '' }}
                <br>{{ $address->country_name ?? '' }}</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col col-lg-6">

            <div class="form-group">
                <input type="hidden" name="address_id" id="address_id" data-error-container="#address-error" value="">
                <div id="address-error"></div>
            </div>

            <a tabindex="6" class="new_address_form"> <i class="fa fa-plus-circle "></i> Add New
                Address</a>
        </div>
    </div>
    <div class="addNewAddressDiv hide">
        <div class="row">
            <div class="col">
                <div class="bg-success clearfix magic-box">
                    <h3 class="">Add New address</h3>
                    <div class="col-lg-12">
                        <div class="alert alert-danger address-guide">
                            Enter your postcode and click on PAF button to search your address !!!
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="country">Country
                                    <span class="required">*</span></label>
                                <select data-live-search="true" id="country" name="country" class="form-control selectpicker" tabindex="21">
                                    <option value="">Select a country…</option>
                                    @if ($countries)
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->country_id }}">{{ $country->country_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="postcode">Postcode/ZIP
                                </label>

                                <div class="postCodeDive ">
                                    <input type="text" name="postcode" id="postcode1" class="form-control txtuppercase" value="" placeholder="Paf code" data-error-container="#paf-error">
                                </div>

                                <div class="input-group pafDataDiv ">
                                     <input type="text" name="postcode" id="postcode" class="form-control txtuppercase" value="" placeholder="Paf code" data-error-container="#paf-error">
                                    <span class="input-group-btn"> <button class="btn btn-default pc-search-icon" id="pc-search-icon" type="button"> PAF</button> </span>
                                </div>

                               <!--  <input type="text" name="postcode" id="postcode" class="form-control txtuppercase"
                                    tabindex="22" data-error-container="#paf-error">
                                <button type='button' id="pc-search-icon"
                                    class="btn input-group-addon pc-search-icon overseas_donor search-form hide"><i
                                        class="fa fa-search "> PAF</i> </button> -->


                                <a href="#paf-data" class="btn btn-primary btn-sm btn-popup paf hide">Details</a>
                                <div id="paf-error"></div>
                            </div>
                        </div>
                        <div class="col col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="city">City <span class="required">*</span></label>
                                <input type="hidden" name="city_id" id="city_id" value="">
                                <select data-live-search="true" name="city" id="city" class="selectpicker form-control  searchOperator">
                                    <option value="">Select City</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="address1">Address 1 <span class="required">*</span></label>
                                <input type="text" name="address1" id="address1"
                                    class="form-control txtcapital" tabindex="24">
                            </div>
                        </div>
                        <div class="col col-md-6 col-lg-4">
                            <div class="form-group ">
                                <label for="address2">Address 2 <span class="required">*</span></label>
                                <input type="text" name="address2" id="address2"
                                    class="form-control txtcapital" tabindex="25">

                            </div>
                        </div>
                        <div class="col col-md-6 col-lg-4">
                            {{--  --}}
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-lg btn-primary" id='new-addr-submit' style="background-color:#0f4f7b">Submit</button>
                        <button type="reset" class="btn btn-lg btn-default" id="reset-add" style="background-color:#17afac; color:white">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

   <!--  <div class="text-center">
        <button type="submit" class="btn btn-lg btn-primary">Add New Address</button>
    </div> -->
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
    var addNewAddress    = '{!! route('addNewAddress') !!}';

</script>

<script src=" {{ URL::asset('assets/scripts/profile.js') }} "></script>
<script src=" {{ 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js' }} "></script>
@endsection
