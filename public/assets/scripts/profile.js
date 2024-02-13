var Profile = function() {

    var profileValidate = function() {

        var form = $('#profile-form');
        var error = $('.form-message', form);

        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            messages: {},

            rules: {
                first_name: {
                    required: true
                        // email: true
                },
                last_name: {
                    required: true
                },
                // country: {
                //     required: true
                // },
                // address1: {
                //     required: true
                // },
                // address2: {
                //     required: true
                // },
                // city: {
                //     required: true
                // },
                // postcode: {
                //     required: true
                // },
                phone: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                }
                // user_password: {
                //     required: true
                // },
                // confirm_password: {
                //     // required: true,
                //     equalTo:'#user_password'
                // }

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

                $.ajax({
                        url: 'updateDonorData',
                        type: 'POST',
                        dataType: 'json',
                        data: $("#profile-form").serialize(),
                        beforeSend: function() {
                            App.blockUI({
                                boxed: true,
                                message: 'Processing...',
                                overlayColor: '#f00',
                                target: $('#profile-form')
                            });
                        }
                    })
                    .done(function(response) {
                        if (response.success) {

                            // alert("gfghf  "+response.message);
                            var html_div = "<div class='alert alert-success'>";
                            html_div += response.message;
                            html_div += "</div>";

                            $('#error-msg').html(html_div);
                            $(window).scrollTop(0);
                            location.reload();

                        } else {
                            var html_div = "<div class='alert alert-danger'>";
                            html_div += response.message;
                            html_div += "</div>";

                            $('#error-msg').html(html_div);
                            // error.html('<i class="fa fa-info"></i> Some error.').show();
                            $('#profile-form').trigger('reset');
                        }
                    })
                    .fail(function() {
                        // console.log("error");
                    })
                    .always(function() {
                        App.unblockUI($('#profile-form'));
                    });
            }
        });
       
    }
    $(document).ready(function() {
        // alert($('#country').val());
        // $('.selectpicker').selectpicker('refresh');
        $(document).on('click', '#new-addr-submit', function(event) {

            var country_id = $('#country').val();
            if (country_id == 1) {
                var post_code = $('#postcode').val();
            } else {
                var post_code = $('#postcode1').val();
            }

            var address1 = $('#address1').val();
            var address2 = $('#address2').val();
            var city_id = $('#city').val();
            // alert(country_id);
            // condition to avoid form submit if required field is empty
            if ((country_id == '') || (post_code == '') || (address1 == '') || (city_id == '')) {
                $('#billing-panel-required').removeClass('hide');
                // alert();
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
        $("#country").change(function() {
            console.log('country change event fired');
                var country_id = $(this).val();
                var city_id = $('#city_id').val();
                $('.address-guide').hide();
                // alert(city_id);
                if (country_id == 1) {
                    $('.pc-search-icon').removeClass('hide');
                    $('.address-guide').show().fadeIn( 300 ).delay( 1500 );
                    $('#address1').prop('readonly', true);
                    $('#address2').prop('readonly', true);
                    $('.pafDataDiv').removeClass('hide');
                    $('.postCodeDive').addClass('hide');

                    $('#city').prop('disabled', true);

                } else {
                    $('.pc-search-icon').addClass('hide');
                    $('#address1').prop('readonly', false);
                    $('#address2').prop('readonly', false);
                    $('.postCodeDive').removeClass('hide');
                    $('.pafDataDiv').addClass('hide');

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
                            'city_id': city_id
                        },
                        success: function(result) {
                            // if (result) {
                                // alert(result);
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
            })
            .change();

        $(document).on('click', '.new_address_form', function(event) {
            $('.addNewAddressDiv').removeClass('hide');
            $(".address-radio").prop("checked", false);
        });

        $(document).on('click', '#pc-search-icon', function(event) {
            var post_code = $('#postcode').val();
            // alert("fdfg"+post_code);
            // return false;
            if (post_code != '') {

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
                alert('Please enter your postal code and click on PAF button');
                $('#post_code').focus();
            }
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
                    // $("#city_id").val(data.city_id);
                    // $("#city").val(data.city_name);

                    $('select[name=city]').val(data);
                    $('.selectpicker').selectpicker('refresh');
                    $('.mfp-close').click();

                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    // console.log("complete");
                    App.unblockUI($('#addr_panel'));
                });
            // $('#myModal').modal('hide')
        });
        $(document).on('click','.add-manual-address', function(event){ 
            $('#paf-data').magnificPopup('close');
            // console.log("Checking New ADD");
            $('#address1').prop('readonly', false).val('');
            $('#address2').prop('readonly', false).val('');
            $('#city').prop('disabled', false); 
            $("#city").selectpicker("refresh");
        });
        $(document).on('click','#reset-add', function(){
            $('.addNewAddressDiv').addClass('hide');
        });
    });

    return {
        init: function() {
            profileValidate();
        },
    }
}();
// jQuery(function() {
//     'use strict';
Profile.init();
// });