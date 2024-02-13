$(document).ready(function() {
    $(document).on('click', '.camount', function() {
        var amount = $(this).val();
        $("#hidden_val").val(amount);
    })

    /* Add Participant Event */
    $(document).on('click', '.add-participant', function(e) {
        var _$amount = $('.programs:checked').data('rate')
        var _$val = $('.programs:checked').val()
        _$count = $('#participant-group').data('count') + 1;
        // console.log(_$count)
        /* $('#participant-group').append('<div class="row">' +
            '<div class="col col-md-8">' +
            '<div class="input-group mt-10 ">' +
            '<input type="text" name="participant_name[]" id="participant_name_' + _$count + '" data-id="" class="form-control form-control-sm " data-error-container="#participant-error-' + _$count + '" placeholder="Participant Name" >' +
            '<span class="input-group-addon"> <i class="fa fa-gbp"></i> 25 </span>' +
            '</div>' +
            '<div id="#participant-error-' + _$count + '"></div>' +
            '</div>' +
            '<div class="col col-md-4 mt-10">' +
            '<button class="btn btn-danger delete-participant"><i class="fa fa-trash"></i></button>' +
            '</div>' +
            '</div>'); */
        $('#participant-group').append('<div class="row">' +
            '<div class="col col-md-7 col-sm-7 col-xs-8">' +
            '<div class="mt-10 ">' +
            '<input type="text" name="participant_name[]" id="participant_name_' + _$count + '" class="form-control form-control-sm" placeholder="Qurbani Name">' +
            '</div>' +
            '</div>' +
            '<div class="col col-md-3 col-sm-3 hidden-xs">' +
            '<div class="input-group mt-10 ">' +
            '<span class="input-group-addon"> <i class="fa fa-gbp"></i> </span>' +
            '<input type="text" name="" id="" value="' + _$amount + '" class="form-control form-control-sm" placeholder="" readonly>' +
            '</div>' +
            '</div>' +
            '<div class="col col-md-2 col-sm-2 col-xs-2">' +
            '<div class=" mt-10 ">' +
            '<button type="button" class="btn btn-sm btn-danger delete-participant"><i class="fa fa-trash"></i></button>' +
            '</div>' +
            '</div>' +
            '</div>');
        $('#participant-group').data('count', _$count);

        _$total = parseFloat($('#total-amount').text()) + parseFloat(_$amount);
        // console.log(_$total)
        $('#total-amount').text(_$total.toFixed(2))
            // $('#camount').val( _$total.toFixed(2) )

    })

    /* Program Change */
    $(document).on('click', '.donation-form .rbox', function() {

        var _$amount = $('.programs:checked').data('rate')
        var _$val = $('.programs:checked').val()
            // console.log(_$amount)
            // console.log(_$val)

        $('#participant-group').html('<div class="row">' +
            '<div class="col col-md-7 col-sm-7 col-xs-8">' +
            '<div class="mt-10">' +
            '<input type="text" name="participant_name[]" id="participant_name_1" class="form-control form-control-sm" placeholder="Qurbani Name">' +
            '</div>' +
            '</div>' +
            '<div class="col col-md-3 col-sm-3 hidden-xs">' +
            '<div class="input-group mt-10 ">' +
            '<span class="input-group-addon"> <i class="fa fa-gbp"></i> </span>' +
            '<input type="text" name="" id="" value="' + _$amount + '" class="form-control form-control-sm" placeholder="" readonly>' +
            '</div>' +
            '</div>' +
            '</div>');

        $('#total-amount').text(_$amount)
        $('#camount').val(_$amount)

        var shares = 1;
        if ($('#x_source').val() == 'home') {
            var shares = $('#x_shares').val();
        }

        if (shares > 1) {
            for (i = 0; i < 6; i++) {
                $('.add-participant').trigger('click');
            }
        }
        $('#x_shares').val(1);
    });

    /* Delete Participant Event */
    $(document).on('click', '.delete-participant', function(e) {
        var _$el = $(this);
        var _$amount = $('.programs:checked').data('rate')
        _$total = parseFloat($('#total-amount').text()) - parseFloat(_$amount);
        // console.log(_$total)
        $('#total-amount').text(_$total.toFixed(2))
            // $('#camount').val( _$total.toFixed(2) )

        _$el.closest('.row').remove();
    })

    $(document).on('click', '.donation-form #custom_amount', function() {
        if (!$(".donation-form #custom_amount").hasClass('input-colored-bg')) {
            $(".donation-form #custom_amount").addClass("input-colored-bg");
            $(".donation-form .rbox2 input").prop('checked', false);
        }
    });

    $(document).on('click', '.donation-form .rbox2 label', function() {
        if ($("#custom_amount").hasClass('input-colored-bg')) {
            $("#custom_amount").removeClass("input-colored-bg");
        }
    });

    $(document).on('keyup', '.donation-form #custom_amount', function() {

        var get_this_value = jQuery(this).val().trim();
        if (!isNaN(get_this_value)) {
            if (get_this_value.length < 8) {

                jQuery("#amount").val(parseFloat(get_this_value));
                jQuery("#offline_payment_amount").val(parseFloat(get_this_value));
                if (get_this_value.length == 0) {
                    jQuery("#amount").val(10.00);
                    jQuery("#offline_payment_amount").val(10.00);
                }

            } else {
                alert("Max length 8 digit");
                jQuery(this).val("");
            }
        } else {
            alert('Input number value');
            jQuery(this).val("");
        }

    });

    $(document).on('keyup', '#custom_amount', function() {

        var get_this_value = jQuery(this).val().trim();
        jQuery("#donation_amount").text(parseFloat(get_this_value));
        jQuery("#amount").text(parseFloat(get_this_value));

        if (get_this_value.length == 0) {
            jQuery("#donation_amount").text(10.00);
            jQuery("#amount").text(10.00);
        }
    });

    /* donation type change event */
    $(document).on('change', '#donation_type', function() {

        var _$donation_type_id = $(this).val();

        if (_$donation_type_id) {

            $('.other-input-box').addClass('display-hide')
            $('#country-group').addClass('display-hide')
            $('#amount-group').addClass('display-hide')

            $.ajax({
                    url: program_route,
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        'category_id': _$donation_type_id
                    },
                    beforeSend: function() {
                        App.blockUI({
                            message: 'Please wait...',
                            overlayColor: '#238650',
                        });
                    }
                })
                .done(function(response) {
                    $('#program-group').html(response);
                    $('#program-group').removeClass('display-hide');

                    $('#programs').selectpicker()
                })
                .fail(function(textStatus) {

                })
                .always(function() {
                    App.unblockUI();
                });
        }
    });

    /* program change event */
    $(document).on('change', '#programs', function() {

        var _$program_id = $(this).val();

        if (_$program_id) {

            $('.other-input-box').addClass('display-hide')
            $('#amount-group').addClass('display-hide')

            $.ajax({
                    url: program_country_route,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'program_id': _$program_id
                    },
                    beforeSend: function() {
                        App.blockUI({
                            message: 'Please wait...',
                            overlayColor: '#238650',
                        });
                    }
                })
                .done(function(response) {
                    if (response) {
                        if (response.other_input_box == 'Y') {

                            $('.other-input-box').removeClass('display-hide')
                            $('#amount-group').removeClass('display-hide');
                            $('.rbox-wrap-amount').html(response.amount_box);
                            $('#country-group').html(response.country_input);

                        } else {

                            $('#country-group').html(response.country_input);
                            $('#country-group').removeClass('display-hide');
                            $('#country').selectpicker()
                        }

                    }


                })
                .fail(function(textStatus) {

                })
                .always(function() {
                    App.unblockUI();
                });
        }
    });

    /* country change event */
    $(document).on('change', '#country', function() {

        var _$country_id = $(this).val();
        var _$donation_type = $('#donation_type').val();

        if (_$country_id) {

            $('.other-input-box').addClass('display-hide')
            $('#amount-group').addClass('display-hide')

            $.ajax({
                    url: qurbani_program_rate_route,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'category_id': _$donation_type,
                        'country_id': _$country_id
                    },
                    beforeSend: function() {
                        App.blockUI({
                            message: 'Please wait...',
                            overlayColor: '#238650',
                        });
                    }
                })
                .done(function(response) {
                    if (response.success) {

                        $('#program-group').html('');
                        $('#program-group').html(response.html);

                        if ($('#x_source').val() == 'home') {

                            $("input[name=programs][value='" + $('#x_animal').val() + "']").attr("checked", true);
                            var webid = $('input[name=programs]:checked').attr('id');
                            // console.log(webid);
                            $("label[for=" + webid + "]").trigger('click');


                        } else {

                            $('.p1').trigger('click');
                        }

                        // $('.p1').trigger('click');

                    }

                })
                .fail(function(textStatus) {

                })
                .always(function() {
                    App.unblockUI();
                });
        }

    });

    /* remove item from cart event */
    $(document).on('click', '.form-cart-item-remove', function() {
        var self = $(this);
        var $cart_id = self.closest('tr').find('td.form-cart-item-remove .cart_id').val();

        $.post(delete_item, { 'cart_id': $cart_id }, function(data, textStatus, xhr) {
            if (data.success) {
                $('.one_off_total').html('<i class="fa fa-gbp"></i> ' + data.one_off_total)
                $('.monthly_total').html('<i class="fa fa-gbp"></i> ' + data.monthly_total)
                self.closest('tr').remove();
                window.location.hash = '#cart-section';
                window.location.reload()
            }
        }, 'json');
    })
});

var Welcome = function() {
    var donationFormValidate = function() {

        var form = $('#donation-form');
        var error = $('.form-message', form);

        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            ignore: " ", // validate all fields including form hidden input
            // errorLabelContainer: ".form-message",
            // wrapper: "li",
            messages: {
                /* donation_type: {
                    required: "Please select donation type *"
                }, */
                'participant_name[]': {
                    required: "Please enter participant name *"
                },
                programs: {
                    required: "Please select programs *"
                },
                /* camount: {
                    required: "Please select amount *"
                } */
            },

            rules: {
                /* donation_type: {
                    required: true
                }, */
                'participant_name[]': {
                    required: true,
                    maxlength: 20
                },
                programs: {
                    required: true
                },
                /* donation_amount: {
                    required: true
                },
                camount: {
                    required: function(element) {
                        if ($("#custom_amount").val() != '') {
                            return false;
                        } else {
                            return true;
                        }
                    }
                }, */
            },

            errorPlacement: function(error, element) { // render error placement for each input type
                if (element.parents(".input-select-group").length > 0) {
                    error.insertAfter(element.parent(".input-select"));
                } else if (element.parent(".twitter-typeahead").length > 0) {
                    error.insertAfter(element.parent().parent(".input-group"));
                } else if (element.attr("data-error-container")) {
                    error.appendTo(element.attr("data-error-container"));
                } else if (element.parents('.rbox-wrap').length > 0) {
                    error.appendTo(element.parents('.rbox-wrap').attr("data-error-container"));
                } else {
                    error.insertAfter(element); // for other inputs, just perform default behavior
                }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit
                // success1.hide();
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
                if ($('#donation-form').valid()) {
                    $.ajax({
                            url: add_to_cart,
                            type: 'POST',
                            dataType: 'json',
                            data: $("#donation-form").serialize(),
                            beforeSend: function() {
                                App.blockUI({
                                    boxed: true,
                                    message: 'Processing...',
                                    overlayColor: '#f00',
                                });
                            }
                        })
                        .done(function(response) {
                            // window.location.hash = '#cart-section';
                            $('#donation-form').trigger("reset");
                            window.location.reload();
                        })
                        .fail(function(textStatus) {
                            $('#donation-form').trigger("reset");
                            $('.program_rate_section').hide();
                        })
                        .always(function() {
                            App.unblockUI();
                        });
                }
            }
        });


    }

    // INPUT SPINNER
    var spinner = (function() {
        var _$el = $('.input-group-spinner');
        var init = function() {
            _$el.each(function() {
                var self = $(this);
                var $minus = self.find('.input-group-btn:first-of-type .btn');
                var $plus = self.find('.input-group-btn:last-of-type .btn');
                var $input = self.find('input');
                var inputVal = parseInt($input.val(), 10);
                $minus.on('click', function() {
                    if ($input.val() > 1) {
                        var $cart_id = self.closest('tr').find('td.form-cart-item-remove .cart_id').val();
                        var $quantity = --inputVal;
                        $.post(update_quantity, { 'quantity': $quantity, 'cart_id': $cart_id }, function(data, textStatus, xhr) {
                            if (data.success) {
                                $('.one_off_total').html('<i class="fa fa-gbp"></i> ' + data.one_off_total)
                                $('.monthly_total').html('<i class="fa fa-gbp"></i> ' + data.monthly_total)
                                $input.val($quantity);
                                var $amount = self.closest('tr').find('td.form-cart-item-price .form-cart-amount').text();
                                var value = parseFloat($amount) * $input.val();
                                self.closest('tr').find('td.form-cart-item-total .form-cart-amount').html('<i class="fa fa-gbp"></i> ' + value.toFixed(2));
                            }
                        }, 'json');

                    }
                });
                $plus.on('click', function() {
                    var $cart_id = self.closest('tr').find('td.form-cart-item-remove .cart_id').val();
                    var $quantity = ++inputVal;
                    $.post(update_quantity, { 'quantity': $quantity, 'cart_id': $cart_id }, function(data, textStatus, xhr) {
                        if (data.success) {
                            $('.one_off_total').html('<i class="fa fa-gbp"></i> ' + data.one_off_total)
                            $('.monthly_total').html('<i class="fa fa-gbp"></i> ' + data.monthly_total)
                            $input.val($quantity);
                            var $amount = self.closest('tr').find('td.form-cart-item-price .form-cart-amount').text();
                            var value = parseFloat($amount) * $input.val();
                            self.closest('tr').find('td.form-cart-item-total .form-cart-amount').html('<i class="fa fa-gbp"></i> ' + value.toFixed(2));
                        }
                    }, 'json');
                });
                $input.on('blur', function() {
                    if (!$input.val()) {
                        $input.val(1);
                    }
                });
            });
        }
        return {
            init: init
        }
    })();

    return {

        init: function() {
            donationFormValidate();
            spinner.init();
        },

    };
}();

Welcome.init();