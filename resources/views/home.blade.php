@extends('app')

@section('content')
<div class="donate-form-section" id="donate-form">
    <header class="page-header">
        <h1 class="text-warning">Donate Now </h1>
    </header>
    <form class="donation-form" id="donation-form">
        <div class="form-group">
            <label for="payment_method">Payment Type <span class="required">*</span></label>
            <div class="rbox-wrap">
                <div class="rbox">
                    <input type="radio" checked="checked" id="p1" name="payment_method" class="payment_method"
                        value="O">
                    <label for="p1">One-Off</label>
                </div>

                <div class="rbox">
                    <input type="radio" id="p2" name="payment_method" class="payment_method" value="M">
                    <label for="p2">Monthly</label>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>

        <div id="donation-type-group" class="form-group">
            <label for="recurrence">Donation Type <span class="required">*</span></label>

            <select id="donation_type" name="donation_type" class="form-control selectpicker" data-live-search="true">
                <option value="">Select Donation Type</option>
                @if ($categories)
                @foreach ($categories as $category)
                <option value="{{$category->category_id}}">{{$category->category_name}}</option>
                @endforeach
                @endif
            </select>

        </div>

        <div id="program-group" class="form-group display-hide">
            {{-- Program input goes here --}}
        </div>

        <div id="country-group" class="form-group display-hide">
            {{-- Country input goes here --}}
        </div>

        <div id="participant-group" class="form-group display-hide" data-count="1">
            {{-- Pariticipant names input goes here --}}
        </div>

        <div class="row display-hide mb-15" id="participant-summary">
            <div class="col col-md-3 col-xs-4">
                <button type="button" class="btn btn-sm btn-default">Total : <i class="fa fa-gbp"></i> <span
                        id="total-amount">0</span> </button>
                <input type="hidden" name="participant_camount" id="participant_camount" value="0">
                {{-- <input type="hidden" name="custom_amount" value=""> --}}
            </div>
            <div class="col col-md-6 col-xs-6">
                <button type="button" style="background-color:#6c3565 !important" class="btn btn-sm btn-danger add-participant">Add New Plaque</button>
            </div>
        </div>

        <div id="amount-group" class="form-group display-hide" data-error-container="#amount_error">
            <label for="amount">Donation Amount <span class="required">*</span></label>
            <br>

            <div class="rbox-wrap-amount">
                {{-- AMOUNT BOX --}}
            </div>
            <div class="clearfix"></div>
            <div id="amount_error"></div>

            <div class="other-input-box input-group mt-10 display-hide">
                <input type="text" name="custom_amount" id="custom_amount" class="form-control form-control-sm"
                    placeholder="or enter donation amount, e.g: 35">
                <span class="input-group-addon"> <i class="fa fa-gbp"></i> </span>
            </div>

            <input type="hidden" id="hidden_val" value="" />

            @if (isset($_REQUEST['source']) && $_REQUEST['source'] == 'home')
            <input type="hidden" id="x_type" value="{{ $_REQUEST['type'] ?? 'one-off' }}">
            {{-- <input type="hidden" id="x_web_category" value="{{ $_REQUEST['web_category'] }}"> --}}
            <input type="hidden" id="x_source" value="{{ $_REQUEST['source'] }}">
            <input type="hidden" id="x_category_id" value="{{ $_REQUEST['category_id'] ?? "" }}">
            <input type="hidden" id="x_program_id" value="{{ $_REQUEST['program_id'] ?? "" }}">
            <input type="hidden" id="x_country_id" value="{{ $_REQUEST['country_id'] ?? ""}}">
            @endif


        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-lg
                    btn-primary" style="background-color: #0f4f7b !important">Submit donation</button>
        </div>
    </form>
</div>
<hr>
@include('cart')
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
    var	qurbani_route = '{!! route('qurbani_page') !!}';
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

    $(document).ready(function(){
        // trigger donation type
        <?php if(isset($_REQUEST['source']) && $_REQUEST['source'] == 'home'): ?>
        $('#donation_type').val(<?php echo $_REQUEST['category_id'] ?>);
        setTimeout(function(){
            $('#donation_type').trigger('change');
        }, 1000);
        <?php endif; ?>
    });
</script>

<script src=" {{ URL::asset('assets/scripts/donate.js?v='. time()) }} "></script>
<script src=" {{ URL::asset('assets/scripts/login.js?v='. time()) }} "></script>
@endsection
