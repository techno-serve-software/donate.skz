<div id="address-form" class="popup mfp-hide">
    <header class="popup-heading">
        <h2>Add new address</h2>
        <small></small>
    </header>
    <div class="popup-inner">
        <form class="request-form" id="new_address_form">
            <div class="row">
                <div class="col col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="country">Country
                            <span class="required">*</span></label>
                        <select id="country" name="country" class="form-control selectpicker" tabindex="21">
                            <!-- <option value="">Select a country…</option> -->
                            @if ($countries)
                            @foreach ($countries as $country)
                            <option value="{{ $country->country_id }}">{{ $country->country_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="address1">Address 1 <span class="required">*</span></label>
                        <input type="text" name="address1" id="address1" class="form-control" tabindex="24" required>
                    </div>

                </div>
                <div class="col col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="postcode">Postcode/ZIP
                            <span class="required">*</span></label>
                        <input type="text" name="postcode" id="postcode" class="form-control" tabindex="22" required>
                        <span id="pc-search-icon"  class="btn input-group-addon pc-search-icon overseas_donor"><i class="fa fa-search "> PAF</i> </span>
                    </div>
                    <div class="form-group">
                        <label for="address2">Address 2 <span class="required">*</span></label>
                        <input type="text" name="address2" id="address2" class="form-control" tabindex="25" required>

                    </div>
                </div>
                <div class="col col-md-6 col-lg-4">
                    <div class="form-group">
                        <label for="city">City <span class="required">*</span></label>
                       
                        <select name="city" id="city" class="selectpicker form-control  searchOperator">
                            <option value="">SELECT CITY</option>
                        
                        </select>
                    </div>
                </div>

            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-lg btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var addNewAddress = '{!! route('addNewAddress') !!}';
    var city_route    = '{!! route('city') !!}';
    var get_paf_data  = '{!! route('get_paf_data') !!}';
</script>