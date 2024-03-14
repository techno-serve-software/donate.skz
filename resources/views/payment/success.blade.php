@extends('app')
@section('content')
<ol class="breadcrumb">
    <li><a href="{{ url('/')}}">Home</a></li>
    <li class="active">Payment Successful</li>
</ol>
<header class="page-header">
    <h1>Thank <i>you</i></h1>
</header>
<h2>Payment Successful</h2>

@if (request()->status == 'only-one-off' || request()->status == 'both' )
<p class="lead">Thank you <b>{{ Str::title(request()->donor_name) }}</b> for your donation of <b>£ <span class="cvalue">{{ request()->payment}}</span></b>. You made a big difference!</p>
@endif

@if (request()->status == 'only-direct-debit' || request()->status == 'both' )
<p class="lead">Your Direct Debit is created successfully,
    the organization will contact you for confirmation.
    Thank you for supporting SKZ Foundation with your monthly Direct
    Debit. We are dedicated to help alleviate the suffering of
    the world's poorest people, which is not possible
    without generous donations from people like you.
</p>
@endif

<blockquote>
    <p class="text-primary custom">
        The Prophet Muhammad, peace be upon him, said
        that "The most beloved deed to Allah (SWT) is the
        most regular and constant"
    </p>
</blockquote>
<p>
    Your regular gifts will
    help us to plan ahead, ensuring that we offer
    the best long-term help possible, and are always
    ready to answer the call.
</p>
<p>
Your Payment Reference no. is <b>{{ request()->ref }}</b>
<br />
An email has been sent to you with your complete donation details!
</p>

<a href="{{ url('/') }}" class="btn btn-steps"> <i class="fa fa-angle-left"></i> <span>Go Back</span>
</a>

@endsection

@section('footer_scripts')
<script>
    App.cartItemCount();
</script>
{{-- <script src=" {{ URL::asset('assets/scripts/resetPassword.js') }} "></script> --}}
@endsection
