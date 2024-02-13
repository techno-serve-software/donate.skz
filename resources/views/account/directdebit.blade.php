@extends('account.index')

@section('sub_content')
@if (count($directDebits))
<header class="catalog-heading">
    <div class="row">
        <div class="col col-lg-4 col-md-4">
            <div class="catalog-search-result">Showing {{ $directDebits->currentPage() }}–{{ $directDebits->lastPage() }} of
                {{ $directDebits->total() }} results</div>
        </div>
       <div class="col col-lg-4 col-md-4">
            <select name="dd_order_by" id="dd_order_by" class="form-control selectpicker">
                  <option value="asc" {{ (request()->orderby=='asc')?'selected':''}}>Sort by ASC</option>
                <option value="desc" {{ (request()->orderby=='desc')?'selected':''}}>Sort by DESC</option>
            </select>
        </div>
         <div class="col col-lg-4 col-md-4">
            <select name="dd_sort_value" id="dd_sort_value" class="form-control selectpicker">
               <option value="auddis_date" {{ (request()->sort_value=='auddis_date')?'selected':''}} >Auddis date</option>
                <option {{ (request()->sort_value=='program_name')?'selected':''}} value="program_name" >Program</option>
                <option {{ (request()->sort_value=='country_name')?'selected':''}} value="country_name">Country</option>
            </select>
        </div>
    </div>
</header>
<div class="row">

    <div class="col col-lg-12 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">Your direct
                debit
                details</div>
            <div class="panel-body">
                @foreach ($directDebits as $directDebit)
                <h4><a href="#">Reference number:
                        {{$directDebit->direct_debit_ref}}</a></h4>
                <p>Auddis Date : {{$directDebit->auddis_date}} <br>
                    Total Amount Received: {{$directDebit->amount}} <br>
                    Program : {{$directDebit->program_name}} <br>
                    Country : {{$directDebit->country_name}}</p>
                <a id="dd_payment_detail" class="btn
                        btn-primary btn-sm dd_payment_detail" value='{{$directDebit->direct_debit_ref}}'>Details</a> 
                <a href="#direct-debit" class="btn
                        btn-primary btn-sm btn-popup hide">Details</a>
                        
                <hr>
                @endforeach
            </div>
        </div>
    </div>
</div>
<nav aria-label="Page navigation" class="text-center">
    {{ $directDebits->appends(request()->except('page'))->links() }}
</nav>
@else
<div class="section-light">
        <div class="container text-center">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <p><img src="{{ asset('assets/images/icon-donate.png') }}" alt="" width="128" height="128"></p>
                    <header class="heading">
                        <p>You don't have any direct debit.</p>
                        <h3>Setup Your Direct Debit NOW</h3>
                    </header>
                    {{-- <p>Click below button to donate now</p> --}}
                    <a href="{{ url('/') }}" class="btn btn-lg btn-default" style="background-color:#6d3566; color:white !important">Make a donation</a>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@section('modal')

@include('_modal.directDebit')

@endsection

@section('footer_scripts')
<script>
    App.cartItemCount();
</script>
<script src=" {{ URL::asset('assets/scripts/account.js') }} "></script>
@endsection