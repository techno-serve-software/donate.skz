<div class="guest-form hide-">
    <hr>
    <div class="checkout">
        <h2 class="">Your payment details</h2>
        <div class="row">
            <div class="col col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="title">Title <span class="required">*</span></label>
                    <select id="title" name="title" class="form-control selectpicker" tabindex="1">
                        <option  value="Mr">Mr</option>
                        <option  value="Mrs">Mrs</option>
                        <option  value="Ms">Ms</option>
                        <option  value="Miss">Miss</option>
                    </select>
                </div>

            </div>
            <div class="col col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="first_name">First name <span class="required">*</span></label>
                    <input type="text" name="first_name" id="first_name" class="form-control txtfirstcapitaleachword" tabindex="2"
                        value="" placeholder="eg. John" >
                </div>
            </div>
            <div class="col col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="last_name">Last name <span class="required">*</span></label>
                    <input type="text" name="last_name" id="last_name" class="form-control txtfirstcapitaleachword" tabindex="3"
                        value="" placeholder="eg. Doe" >
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="phone">Phone<span class="required">*</span></label>
                    <input type="text" name="phone" id="phone" tabindex="4" class="form-control"
                        value="">
                </div>

            </div>
            <div class="col col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="text" name="email" id="email" tabindex="5" class="form-control"
                        value="" placeholder="john@yoursite.com" >

                </div>
            </div>
            <div class="col col-md-6 col-lg-4">
                {{--  --}}
            </div>
        </div>

        <hr>
        <h2 class="">Your address <span class="required">*</span></h2>
        <p class=""></p>

        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-success address-guide">
                    Enter your postcode and click on PAF button to search your address !!!
                </div>
            </div>
            <div class="col col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="country">Country
                        <span class="required">*</span></label>
                    <select id="country" name="country" class="form-control selectpicker" data-live-search="true" tabindex="21">
                        <!--<option value="">Select a country…</option>-->
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

                    <div class="input-group pafDataDiv ">
                         <input type="text" name="postcode" id="postcode" class="form-control txtuppercase" value="" placeholder="Paf code" data-error-container="#paf-error">
                        <span class="input-group-btn"> <button class="btn btn-default pc-search-icon" id="pc-search-icon" type="button"> PAF</button> </span>
                    </div>

                    <a href="#paf-data" class="btn btn-primary btn-sm btn-popup paf hide">Details</a>
                    <div id="paf-error"></div>
                </div>
            </div>
            <div class="col col-md-6 col-lg-4">
                <div class="form-group">
                    <label for="city">City <span class="required">*</span></label>
                    <input type="hidden" name="city_id" id="city_id" value="">
                    <input type="hidden" name="city_name" id="city_name" value="">
                    <select name="city" id="city" data-live-search="true" class="selectpicker form-control  searchOperator">
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

            </div>
        </div>

    </div>

    @if (in_array('direct-debit', array_column($cart_data, 'donation_period')))
    <hr>
    <h2>Your bank details</h2>
    <div class="row">
        <div class="col col-md-6 col-lg-4">
            <div class="form-group">
                <label for="pay_day">Pay Day <span class="required">*</span></label>
                <select id="pay_day" name="pay_day" class="form-control selectpicker" tabindex="7">
                    <option value="8">8th day of month</option>
                    <option value="31">Last day of month</option>
                </select>
            </div>

        </div>
        <div class="col col-md-6 col-lg-4">
            <div class="form-group">
                <label for="sortcode">Sort Code <span class="required">*</span></label>
                <input type="text" name="sortcode" id="sortcode" class="form-control" placeholder="XX-XX-XX" tabindex="8">
            </div>
        </div>
        <div class="col col-md-6 col-lg-4">
            <div class="form-group">
                <label for="account_number">Account Number
                    <span class="required">*</span></label>
                <input type="text" name="account_number" id="account_number" class="form-control" placeholder=""
                    tabindex="9">
            </div>
        </div>
    </div>
    @endif

    @if (in_array('one-off', array_column($cart_data, 'donation_period')))
    <hr>
    <h2 class="">Payment method</h2>
    <div class="row">
        <div class="col col-lg-4 col-md-4 col-sm-4
                col-xs-4">
            <label for="payment_method">Pay with <span class="required">*</span></label>

            <div class="form-group">
                <div class="radio">
                    @if (config('icharms.payment.stripe'))
                    <label>
                        <input type="radio" name="payment_method" id="payment-method-online" tabindex="10" value="stripe"
                           checked >
                        Stripe
                    </label>
                    &nbsp &nbsp &nbsp &nbsp
                    @endif

                    @if (config('icharms.payment.netpay'))
                    <label>
                        <input type="radio" name="payment_method" id="payment-method-online" tabindex="10" value="netpay"
                            >
                        Net Pay
                    </label>
                    &nbsp &nbsp &nbsp &nbsp
                    @endif

                    @if (config('icharms.payment.paypal'))
                    <label>
                        <input type="radio" name="payment_method" id="payment-method-online" tabindex="10" value="paypal"
                            >
                        Pay Pal
                    </label>
                    @endif
                </div>
            </div>
        </div>
        <div class="col col-lg-8 col-md-8 col-sm-8
                    hidden-xs">
            <img class="pull-right" src="{{ asset('assets/images/payment-icons.png') }}" alt="Payment icons">
        </div>
    </div>
    @endif

    <hr>
    <h2 class="">Additional information</h2>
    <div class="form-group">
        <label for="notes">Reason for donating</label>
        <textarea class="form-control" name="notes" id="notes" rows="10" tabindex="11"></textarea>

    </div>

    <div class="row">
        <div class="col">
            <div class="magic-box bg-dark clearfix">
                <h2 class="text-white">Gift aid</h2>
                <img class="pull-right" src="{{ asset('assets/images/giftaid.png') }}" alt="Gift Aid" width="120"
                    height="48">

                <p>If you are a UK taxpayer, the value of your gift can be increased by 25% under the Gift Aid scheme at
                    no
                    extra cost to you. With Gift Aid, your donation of would be worth , and it doesn't cost you a penny
                </p>

                <h4 class="">Your donation of every £<span id="tax_amount">1.00</span> can become £<span
                        id="tax_detect_amount">1.25</span></h4>
                <p class="lead">
                    <input type="checkbox" name="taxpayer" id="taxpayer" value="Y"> &nbsp; I am a UK taxpayer
                    and would like SKZ Foundation to reclaim tax on all donations I have made within the last four years
                    and
                    all donations that I make hereafter.
                </p>

                <p class="font-12"> <i>* I understand that if I pay less Income Tax and/or Capital Gains tax than the
                        amount of Gift Aid claimed on all my donations by all the charities or Community Amateur Sports
                        Clubs
                        (CASCs) that I donate to will reclaim on my gifts for that tax (6 April to 5 April), it is my
                        responsibility to pay any difference.</i>
                </p>
                <p class="font-12">
                    Please let us know if you want to cancel this declaration, change your home address or no longer pay
                    sufficient tax.
                </p>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="bg-info clearfix magic-box">
                <h3 class="">Stay up-to-date by:</h3>

                <div class="row">
                    <div class="col col-lg-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="send_email" tabindex="12" id="send_email" value="Y">
                                Send me updates by email
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="send_mail" tabindex="13" id="send_mail" value="Y">
                                Contact me by post
                            </label>
                        </div>
                    </div>
                    <div class="col col-lg-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="tele_calling" tabindex="14" id="tele_calling" value="Y">
                                Contact me by phone
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="send_text" tabindex="15" id="send_text" value="Y">
                                Send me updates by SMS
                            </label>
                        </div>
                    </div>
                </div>
                <p class="mt-10">You can opt out any time by contacting us on 020 XX XXXXX or emailing us at
                    enquiry@skzfoundation.uk
                </p>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-lg btn-primary" tabindex="16" style="background-color: #0f4f7b">Donate Now</button>
    </div>

</div>
