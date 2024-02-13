<?php $cart_data = \Session::get('cart'); ?>
<!-- <header class="page-header"> -->
<h2 class="text-center header-2" id="cart-section">Complete your donation</h2>
<!-- </header> -->

@if ($cart_data)
<?php //print_r($cart_data);?>
<form class="cart-form" action="checkout">
    <div class="table-responsive form-cart-items">
        <table class="table table-stripedd">

            <tbody>

                <?php
                $one_off_total = $monthly_total = 0; ?>

                @foreach ($cart_data as $key => $cart)
                <?php
                $participant_names='';
                if($cart->participant_name)
                {
                    $participant_names = explode(",", $cart->participant_name);
                }
                if($cart->donation_period=='one-off')
                    $one_off_total += $cart->donation_amount * $cart->quantity;
                else
                    $monthly_total += $cart->donation_amount * $cart->quantity;

                ?>
                <tr data-row-id="{{ $key + 1 }}">
                    <td class="form-cart-item-remove">
                        <input type="hidden" class="cart_id" name="" value="{{ $cart->cart_id }}" />
                        <a href="javascript:void(0);"><i class="fa fa-2x fa-trash-o"></i></a>
                    </td>
                    <td class="form-cart-item-photo">
                        @if ($cart->program_image != null)
                        <img src="{{ $cart->program_image ?? '' }}"
                            width="100" height="67" alt="Program Image">
                        @else
                        <img src="{{asset('assets/images/no-image.png')}}" width="100" height="67"
                            alt="Program Image">
                        @endif
                    </td>
                    <td>{{ ucfirst( $cart->donation_period ) }}</td>
                    <td class="form-cart-item-title">

                        {{ $cart->category_name . ' - '. $cart->program_name . ' ( ' . $cart->country_name . ' ) ' }}

                        @if($participant_names)
                        <br>
                        @foreach($participant_names as $participant_name)
                        <span class="badge badge-primary">{{$participant_name}}</span>
                        <br>
                        @endforeach
                        @endif
                        @if ($cart->program_status == 'N')
                        <p class="text-danger">The program is now inactivated</p>
                        @endif
                    </td>
                    <td class="form-cart-item-price">
                        <span class="form-cart-amount"><i class="fa fa-gbp"></i> {{ $cart->donation_amount }}</span>
                    </td>
                    @if ($participant_names)
                    <td class="form-cart-item-quantity" data-toggle="tooltip" data-placement="left"
                        title="{{ 'To add more '.$cart->program_name.'(s), Please add from the Participant Name'}}">
                        <div class="input-group
                                            input-group-spinner">
                            <span class="input-group-btn">
                                <button class="btn
                                                    btn-default btn-minus" type="button" disabled><i class="fa
                                                        fa-minus"></i></button>
                            </span>
                            <input type="text" class="form-control" value="{{ $cart->quantity }}" disabled>
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-plus" type="button" disabled><i
                                        class="fa fa-plus"></i></button>
                            </span>
                        </div>
                    </td>
                    @else
                    <td class="form-cart-item-quantity" data-toggle="tooltip" data-placement="left" title="">
                        <div class="input-group
                                            input-group-spinner">
                            <span class="input-group-btn">
                                <button class="btn
                                                    btn-default btn-minus" type="button"><i class="fa
                                                        fa-minus"></i></button>
                            </span>
                            <input type="text" class="form-control" value="{{ $cart->quantity }}">
                            <span class="input-group-btn">
                                <button class="btn btn-default btn-plus" type="button"><i
                                        class="fa fa-plus"></i></button>
                            </span>
                        </div>
                    </td>
                    @endif
                    <td class="form-cart-item-total">
                        <span class="form-cart-amount"><i class="fa fa-gbp"></i>
                            {{ number_format($cart->donation_amount * $cart->quantity, 2) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col col-lg-4 col-md-4 margin-bottom-sm">
        </div>
        <div class="col col-lg-8 col-md-8">
            <h2>Donation totals</h2>
            <table class="table table-bordered">
                <tbody>
                    @if ($one_off_total)
                    <tr>
                        <th>One-off Total</th>
                        <th class="one_off_total"><i class="fa fa-gbp"></i> {{ number_format($one_off_total, 2) }}</th>
                    </tr>
                    @endif
                    @if ($monthly_total)
                    <tr>
                        <th>Monthly Total</th>
                        <th class="monthly_total"><i class="fa fa-gbp"></i> {{ number_format($monthly_total, 2) }}</th>
                    </tr>
                    @endif
                </tbody>
            </table>
            <a href="#donate-form" class="btn btn-md
                                btn-default
                                mb-sm-10" style="background-color:#17afac !important; color:White" >Add more donations</a>
            <button type="submit" class="btn btn-md
                                btn-primary" style="background-color:#0f4f7b !important">Proceed to Payment</button>
        </div>
    </div>
</form>
@else
<div class="text-center">
    <p class="font-15"> <i class="fa fa-shopping-cart"></i> Your cart is empty. <a href="#donate-form">Add Donation</a>
    </p>
</div>
@endif
