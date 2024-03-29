@extends('app')
@section('content')
<ol class="breadcrumb">
    <li><a href="{{ url('/')}}">Home</a></li>
    <li class="active">Payment Cancel</li>
</ol>
<header class="page-header">
    <h1>Something went <i>wrong</i></h1>
</header>
<h2>Payment Cancelled</h2>
<p class="lead text-danger">We're sorry about that. Feel free to contact
    the team if you are having difficulties. </p>

<p class="text-black">Unfortunately your payment has not been successful.</p>

<p class="text-black">Please contact <span class="text-primary">SKZ Foundation </span> for further details: <a
        href="tel:+44 7818 318526"><i class="fa fa-phone"></i> +44 7818 318526</a> &nbsp; or &nbsp; <a
        href="mailto:info@skzfoundation.uk"><i class="fa fa-envelope"></i> info@skzfoundation.uk</a>. We will get back
    to you as soon as possible.</p>

<a href="{{ url('/') }}" class="btn btn-steps"> <i class="fa fa-angle-left"></i> <span>Go Back</span>
</a>

@endsection

@section('footer_scripts')
<script>
    App.cartItemCount();
</script>
{{-- <script src=" {{ URL::asset('assets/scripts/resetPassword.js') }} "></script> --}}
@endsection
