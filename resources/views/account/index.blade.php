@extends('app')

@section('content')
<style>
    .list-group-item.active, .list-group-item.active:hover, .list-group-item.active:focus {
            z-index: 2;
            color: #f9f6f6;
            background-color: #0f4f7b;
            border-color: #0f4f7b;
        }
</style>
<ol class="breadcrumb">
    <li><a href={{ url('/') }}>Home</a></li>
    <li class="active">{{ $title }}</li>
    <span style="float:right"><a href={{ url('/') }}>Go to Donation Page</a></span>
</ol>

<header class="page-header">
    <h1 class="text-warning">{{ $title }}</h1>
</header>

<div class="row">
    <div class="col col-lg-3 col-md-3 col-sm-4">
        <div class="profile">
            <div class="photo">
                <img src="{{ asset('assets/images/user.png') }}" width="300" height="300" alt="">
            </div>
            <h3>{{ \Session::get('user')['first_name'] }}</h3>
        </div>
        <div class="list-group">
            <a href="{{ action('AccountController@profile') }}"
                class="list-group-item {{ Request::is('account/profile') ? 'active' : '' }} ">
                <i class="fa fa-user-circle-o"></i> Profile
                details
            </a>
            <a href="{{ action('AccountController@changePassword') }}"
            class="list-group-item {{ Request::is('account/changePassword') ? 'active' : '' }} ">
            <i class="fa fa-solid fa-lock"></i> Change Password
            </a>
             <a href="{{ action('AccountController@address') }}" class="list-group-item {{ Request::is('account/address') ? 'active' : '' }}"><i class="fa
                    fa-address-card-o"></i> Your Address</a>
            <a href="{{ action('AccountController@donation') }}"
                class="list-group-item {{ Request::is('account/donation') ? 'active' : '' }}">
                <span class="badge">{{ $totalDonation->total_records ?? ''}}</span>
                <i class="fa fa-heart"></i> My Donations
            </a>
            <a href="{{ action('AccountController@direct_debit') }}"
                class="list-group-item {{ Request::is('account/direct-debit') ? 'active' : '' }}">
                <span class="badge">{{ $totalDD ?? ''}}</span>
                <i class="fa fa-heart-o"></i>
                My Direct Debits
            </a>
           
            <a href="{{ action('LoginController@logout') }}" class="list-group-item"><i class="fa
                    fa-sign-out"></i> Logout</a>
        </div>
    </div>
    <div class="col col-lg-9 col-md-9 col-sm-8">

        @yield('sub_content')
    </div>
</div>

@endsection