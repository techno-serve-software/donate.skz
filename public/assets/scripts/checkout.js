var Checkout = (function($) {
    'use strict';

    var checkoutFormValidate = function() {

        var form = $('#checkout-form');
        var error = $('.form-message', form);

        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            // errorLabelContainer: ".form-message",
            // wrapper: "li",
            messages: {
                title: {
                    required: 'Please select title *'
                },
                first_name: {
                    required: 'Please enter your first name *'
                },
                last_name: {
                    required: 'Please enter your last name *'
                },
                address_id: {
                    required: 'Please select address or add new address *'
                },
                phone: {
                    required: 'Please enter your phone *'
                },
                pay_day: {
                    required: 'Please select pay day *'
                },
                sortcode: {
                    required: 'Please enter your sort code *'
                },
                account_number: {
                    required: 'Please enter your account number *'
                },
                payment_method: {
                    required: 'Please select payment method *'
                },
                email: {
                    required: 'Please enter a valid email *'
                },
            },

            rules: {
                title: {
                    required: true
                },
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                },
                address_id: {
                    required: true
                },
                country: {
                    required: function() {
                        return $("#address1").is(":visible");
                    }
                },
                address1: {
                    required: function() {
                        return $("#address1").is(":visible");
                    }
                },
                address2: {
                    required: function() {
                        return $("#address2").is(":visible");
                    }
                },
                postcode: {
                    required: function() {
                        return $("#postcode").is(":visible");
                    }
                },
                pay_day: {
                    required: true
                },
                sortcode: {
                    required: true
                },
                account_number: {
                    required: true
                },
                payment_method: {
                    required: true
                },
                email: {
                    required: true,
                    email: true,
                },
            },

            errorPlacement: function(error, element) { // render error placement for each input type
                if (element.parents(".input-select-group").length > 0) {
                    error.insertAfter(element.parent(".input-select"));
                } else if (element.parent(".twitter-typeahead").length > 0) {
                    error.insertAfter(element.parent().parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('#amount-group').length > 0) {
                    error.appendTo(element.parents('#amount-group').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit
                error.hide();
            },

            highlight: function(element) { // hightlight error inputs

                $(element).closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function(element) { // revert the change done by hightlight
                $(element).closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function(label) {
                label.closest('.form-group').removeClass('has-error'); // set success class to the control group
            },

            submitHandler: function(form) {

                // form.submit();

                $.ajax({
                        url: JsVariables.donate,
                        type: 'POST',
                        dataType: 'json',
                        data: $("#checkout-form").serialize(),
                        beforeSend: function() {
                            App.blockUI({
                                boxed: true,
                                message: 'Processing...',
                                overlayColor: '#f00',
                            });
                        }
                    })
                    .done(function(response) {
                        if (response.success) {
                            // return;
                            if (response.data && response.data.payment_method == 'stripe') {
                                stripeCheckout(response.donor_session, response.reference_id);
                            } else if(response.type && response.type == 'only-recurring'){
                                window.location = response.url;
                            }
                            else {
                                // paypal
                                setLoading(true);
                                window.location = response.url;
                            }
                        }
                    })
                    .fail(function(textStatus) {
                        $('#checkout-form').trigger("reset");
                    })
                    .always(function() {
                        App.unblockUI();
                    });
            }
        });


    }

    var addNewAddressFormValidate = function() {


        var form = $('#new_address_form');
        var error = $('.form-message', form);

        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            messages: {},

            rules: {

            },

            invalidHandler: function(event, validator) { //display error alert on form submit
                error.hide();
            },

            highlight: function(element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').addClass('has-error'); // set error class to the control group
            },

            unhighlight: function(element) { // revert the change done by hightlight
                $(element)
                    .closest('.form-group').removeClass('has-error'); // set error class to the control group
            },

            success: function(label) {
                label
                    .closest('.form-group').removeClass('has-error'); // set success class to the control group
            },

            submitHandler: function(form) {
                error.hide();
                var el = $('.modal-content');
                alert(addNewAddress);
                $.ajax({
                        url: addNewAddress,
                        type: 'POST',
                        dataType: 'json',
                        data: $("#new_address_form").serialize(),
                        beforeSend: function() {
                            App.blockUI({
                                boxed: true,
                                message: 'Processing...',
                                overlayColor: '#f00',
                                target: $('#new_address_form')
                            });
                        }
                    })
                    .done(function(response) {
                        if (response.success) {

                            location.reload();

                        } else {
                            error.html('<i class="fa fa-info"></i> Some error.').show();
                            $('#profile-form').trigger('reset');
                        }
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        App.unblockUI($('#profile-form'));
                    });
            }
        });
    }

    var pageActions = function() {

        $(document).on('click', '#new-addr-submit', function(event) {

            var country_id = $('#country').val();
            var post_code = $('#postcode').val();
            var address1 = $('#address1').val();
            var address2 = $('#address2').val();
            var city_id = $('#city').val();
            // alert(country_id);
            // condition to avoid form submit if required field is empty
            if ((country_id == '') || (post_code == '') || (address1 == '') || (city_id == '')) {
                $('#billing-panel-required').removeClass('hide');

                setTimeout(function() {
                    $('#billing-panel-required').addClass('hide');
                }, 3000);
                return false;
            }

            var formData = new FormData();

            // Push data into FormData object
            formData.append("country_id", country_id);
            formData.append("post_code", post_code);
            formData.append("address1", address1);
            formData.append("address2", address2);
            formData.append("city_id", city_id);
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));

            var request = new XMLHttpRequest();

            // Set up request
            request.open("POST", addNewAddress);

            // Send data
            request.send(formData);

            // Define what happens on successful data submission
            request.addEventListener('load', function(event) {
                var response = $.parseJSON(event.target.responseText);
                location.reload();

            });

            // Define what happens in case of error
            request.addEventListener('error', function(event) {
                alert('Oops! Something went wrong.');
            });
        });

        $(document).on('change', '#country', function(event) {
            console.log('country change event fired');
            var country_id = $(this).val();
            var city_id = $('#city_id').val();
            $('.address-guide').hide();
            // alert(country_id);
            if (country_id == 1) {
                $('.pc-search-icon').removeClass('hide');
                $('.address-guide').show().fadeIn(300).delay(1500);
                // alert("Enter your postcode and click on PAF button to search your address ");
                $('#address1').prop('readonly', true);
                $('#address2').prop('readonly', true);
                $('#city').prop('disabled', true);
            } else {
                $('.pc-search-icon').addClass('hide');

                $('#postcode').val('');
                $('#address1').prop('readonly', false).val('');
                $('#address2').prop('readonly', false).val('');
                $('#city').prop('disabled', false);
            }

            // event.preventDefault();
            // var route = city_route;
            // alert(addNewAddress);
            // return false;
            if (country_id) {
                $.ajax({
                    url: city_route,
                    type: "POST",
                    // dataType: 'json',
                    data: {
                        'country_id': country_id,
                        // 'city_id': city_id
                    },
                    success: function(result) {
                        // console.log(result);
                        // if (result) {
                        $('#city').html('');
                        $('#city').html(result);
                        $("#city").selectpicker("refresh");
                        // }
                    },

                    error: function(data) {

                        $('.error-message').html("Something went wrong.");
                        error.show().delay(4000).fadeOut();
                        console.log('error');
                    },
                    complete: function(data) {
                        // App.scrollTo(error, -200);
                        console.log('complete');
                        // App.unblockUI(el);
                    }
                });
            }
            /* Act on the event */
        });
        // $(document).on('change','#country', function(event){
        //     var country_id = $(this).val();
        //     var city_id = $('#city_id').val();
        //     if (country_id == 'United Kingdom') {
        //         alert(city_id);
        //     }
        // })

        $(document).on('click', '.new_address_form', function(event) {
            $('.addNewAddressDiv').removeClass('hide');
            $(".address-radio").prop("checked", false);

            $('#country').trigger('change');
        });

        $(document).on('click', '.address-radio', function(event) {
            $('#address_id').val($(this).val());
            $('.addNewAddressDiv').addClass('hide');
        });

        $(document).on('click', '#pc-search-icon', function(event) {
            var post_code = $('#postcode').val();

            if ($('#postcode').valid() && post_code != '') {

                $.ajax({
                    url: get_paf_data,
                    type: "POST",
                    // dataType: 'json',
                    data: {
                        'post_code': post_code
                    },
                    success: function(result) {
                        $('.pafData').html("");
                        $('.pafData').html(result);

                        $('.paf').click();

                        $('#paf-data-table').DataTable();

                    },

                    error: function(data) {

                        $('.error-message').html("Something went wrong.");
                        error.show().delay(4000).fadeOut();
                        console.log('error');
                    },
                    complete: function(data) {
                        // App.scrollTo(error, -200);
                        console.log('complete');
                        // App.unblockUI(el);
                    }
                });
            } else {
                $('#post_code').focus();
            }
        });

        // var table = $('#paf-data-table');
        // $('#paf-data-table tbody').on('click', 'tr', function() {
        //                          if ($(this).hasClass('highlight')) {
        //                              $(this).removeClass('highlight');
        //                          } else {
        //                              $('tr.highlight').removeClass('highlight');
        //                              $(this).addClass('highlight');
        //                          }
        //                      });
        $(document).on('click','.add-manual-address', function(event){ 
            $('#paf-data').magnificPopup('close');
            // console.log("Checking New ADD");
            $('#address1').prop('readonly', false).val('');
            $('#address2').prop('readonly', false).val('');
            $('#city').prop('disabled', false); 
            $("#city").selectpicker("refresh");
        });
        $('#paf-data-table tbody').on('dblclick', 'tr', function() {
            var address1 = $(this).children().eq(1).text();
            var address2 = $(this).children().eq(2).text();
            var post_code = $(this).children().eq(3).text();
            var city_name = $(this).children().eq(4).text();
            var country = $(this).children().eq(5).text();
            // alert(address1);
            $.ajax({
                    url: city_name_route,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        "city_name": city_name
                    },
                    beforeSend: function() {
                        App.blockUI({
                            boxed: true,
                            message: 'Processing...',
                            overlayColor: '#f00',
                            target: $('#addr_panel')
                        });
                    }
                })
                .done(function(data) {
                    $("#post_code").val(post_code);
                    $("#address1").val(address1);
                    $("#address2").val(address2);
                    $("#city_id").val(data);
                    $("#city_name").val(city_name);

                    $('select[name=city]').val(data);
                    $('.selectpicker').selectpicker('refresh');
                    $('.mfp-close').click();

                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {

                    App.unblockUI($('#addr_panel'));
                });
        });

        $(document).on('click', '.guest-login', function() {
            $('.guest-form').removeClass('hide');
            $('.user-login-btns').hide();

            $('#country').trigger('change');
        });

        $('#country').trigger('change');

        $(document).on('click','#reset-add', function(){
            $('.addNewAddressDiv').addClass('hide');
        });
    }

    return {
        init: function() {
            checkoutFormValidate();
            addNewAddressFormValidate();
            pageActions();
        }
    }
}(jQuery));

Checkout.init();
