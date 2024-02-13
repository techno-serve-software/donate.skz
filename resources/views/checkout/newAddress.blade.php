@extends('app')
 <link
        href='https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css'
        rel='stylesheet' type='text/css'>

@section('content')
<style type="text/css">

    .search-form span
    {
        position: absolute;
        right: 0;
        top: 68%;
        width: 60px;
        height: 43px;
        background: none;
        border: none;
        color: #328152;
        cursor: pointer;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        transform: translateY(-50%);
    }
</style>
<header class="page-header">
    <h1 class="text-warning"></h1>
</header>

<div class="row">

    <div class="col col-lg-9 col-md-9 col-sm-8">

        <form class="request-form" id="new_address_form">
            <div class="row">
                <div class="col col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="country">Country
                            <span class="required">*</span></label>
                        <select id="country" data-live-search="true" name="country" class="form-control selectpicker" tabindex="21">
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
                    <div class="form-group search-form">
                        <label for="postcode">Postcode/ZIP
                            </label>
                        <input type="text" name="postcode" id="postcode" class="form-control" tabindex="22" required>
                        <span id="pc-search-icon"  class="btn input-group-addon pc-search-icon overseas_donor search-form hide"><i class="fa fa-search "> PAF</i> </span>
                        <a href="#PAF-DATA" class="btn
                        btn-primary btn-sm btn-popup hide">Details</a>

                    </div>
                </div>
                <div class="col col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="city">City <span class="required">*</span></label>

                        <select name="city" data-live-search="true" id="city" class="selectpicker form-control  searchOperator">
                            <option value="">SELECT CITY</option>

                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="address1">Address 1 <span class="required">*</span></label>
                        <input type="text" name="address1" id="address1" class="form-control txtcapital" tabindex="24" required>
                    </div>

                </div>
                <div class="col col-md-6 col-lg-4">
                    <div class="form-group ">
                        <label for="address2">Address 2 <span class="required">*</span></label>
                        <input type="text" name="address2" id="address2" class="form-control txtcapital" tabindex="25" required>

                    </div>
                </div>
                <div class="col col-md-6 col-lg-4">
                    {{--  --}}
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-lg btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

@endsection
@section('modal')
@include('_modal.pafdata')

@section('footer_scripts')

<script src=" {{ 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js' }} "></script>

<script type="text/javascript">

    var addNewAddress = '{!! route('addNewAddress') !!}';
    var city_route    = '{!! route('city') !!}';
    var city_name_route    = '{!! route('city_name') !!}';
    var get_paf_data  = '{!! route('get_paf_data') !!}';

</script>
<script src=" {{ URL::asset('assets/scripts/checkout.js') }} "></script>
@endsection
