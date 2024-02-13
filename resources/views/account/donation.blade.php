@extends('account.index')

@section('sub_content')
@if(count($donations))
<header class="catalog-heading">
    <div class="row">
        <div class="col col-lg-4 col-md-4">
            <div class="catalog-search-result">Showing {{ $donations->currentPage() }}–{{ $donations->lastPage() }} of
                {{ $donations->total() }} results</div>
        </div>
        <div class="col col-lg-4 col-md-4">
            <select name="order_by" id="order_by" class="form-control selectpicker">
                <option value="asc" {{ (request()->orderby=='asc')?'selected':''}}>Sort by ASC</option>
                <option value="desc" {{ (request()->orderby=='desc')?'selected':''}}>Sort by DESC</option>
            </select>
        </div>
        <div class="col col-lg-4 col-md-4">
            <select name="sort_value" id="sort_value" class="form-control selectpicker">

                <option value="donation_date" {{ (request()->sort_value=='donation_date')?'selected':''}}>Donation date
                </option>
                <option {{ (request()->sort_value=='program_name')?'selected':''}} value="program_name">Program</option>
                <option {{ (request()->sort_value=='country_name')?'selected':''}} value="country_name">Country</option>
            </select>
        </div>
    </div>
</header>
<div class="row">
    <div class="col col-lg-12 col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-left">
                    Your donation details
                </div>
                <div class="pull-right text-bold">
                    Total Donations : {{ $totalDonation->total_donation_amount }} GBP
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                @foreach ($donations as $donation)
                <h4>
                    <a class="pull-left">Payment through
                        {{ $donation->created_by == 'website' ? 'Online' : 'Offline' }}</a>
                    <span class="pull-right text-primary"> <i class="fa fa-gbp"></i> {{$donation->donation_amount}}
                    </span>
                    <div class="clearfix"></div>
                </h4>

                <p>Donation Date : {{$donation->donation_date}} <br> Program : {{$donation->program_name}} <br> Country
                    : {{$donation->country_name}}</p>
                <hr>
                @endforeach

            </div>
        </div>
    </div>
    <nav aria-label="Page navigation" class="text-center">
        {{ $donations->appends(request()->except('page'))->links() }}
    </nav>
</div>
@else
<div class="section-light">
    <div class="container text-center">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8">
                <p><img src="{{ asset('assets/images/icon-donate.png') }}" alt="" width="128" height="128"></p>
                <header class="heading">
                    <p>You don't have any donation.</p>
                    <h2>Become a sponsor</h2>
                </header>
                {{-- <p>Click below button to donate now</p> --}}
                <a href="{{ url('/') }}" class="btn btn-lg btn-default" style="background-color:#0f4f7b; color:white !important">Make a donation</a>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('footer_scripts')
<script>
    App.cartItemCount();
</script>
<script src="{{ URL::asset('assets/scripts/account.js') }}"></script>
@endsection