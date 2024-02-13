$(document).ready(function() {
    $(document).on('click', '.camount', function() {
        var amount = $(this).val();
        $("#hidden_val").val(amount);
    })

    if ($('#x_type').val() == 'direct-debit') {
        $('[name=payment_method]').val('M');
        $('#p2').trigger('click');
        // alert($('[name=payment_method]').val())
    }
    // $(document).on('keypress','.participant_name[]',function(e) {
    //         var key = e.keyCode;
    //         if (key >= 48 && key <= 57) {
    //             e.preventDefault();
    //         }
    //     });

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
            // alert('Input number value');
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

    var emptyAmountFields = function() {
        $('#custom_amount').val('');
        $('#participant_camount').val('');
        $('input[name=camount]').val('');
        $('input[name="participant_name[]"]').val('');
    }

    /* donation type change event */
    $(document).on('change', '#donation_type', function() {

        var _$donation_type_id = $(this).val();

        if (_$donation_type_id) {

            emptyAmountFields();

            /* if (_$donation_type_id == 5) {
                window.location.replace(qurbani_route);
            } */

            $('.other-input-box').addClass('display-hide')
            $('#country-group').addClass('display-hide')
            $('#participant-group').addClass('display-hide')
            $('#participant-summary').addClass('display-hide')
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

                    /* New Step */
                    if ($('#x_source').val() == 'home') {
                        // trigger program
                        $('#programs').val($('#x_program_id').val()).trigger('change');
                    }

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
        var _$participant_required = $(this).find(':selected').data('participant-required');

        if (_$program_id) {

            emptyAmountFields();

            $('.other-input-box').addClass('display-hide')
            $('#amount-group').addClass('display-hide')
            $('#participant-group').addClass('display-hide')
            $('#participant-summary').addClass('display-hide')

            $.ajax({
                    url: program_country_route,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'program_id': _$program_id,
                        'participant_required': _$participant_required
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
                            $('#country').selectpicker();

                            /* New Step */
                            if ($('#x_source').val() == 'home') {
                                // trigger country
                                $('#country').val($('#x_country_id').val()).trigger('change');
                            } else {

                                $('#country').trigger('change')
                            }

                        }
                        // NOTE:default country set to PAK
                        $('#country').val('2').trigger('change')
                    }


                })
                .fail(function(textStatus) {
                    console.log(textStatus)
                })
                .always(function() {
                    App.unblockUI();
                });
        }
    });

    /* country change event */
    $(document).on('change', '#country', function() {

        var _$country_id = $(this).val();
        var _$program_id = $('#programs').val();
        var _$participant_required = $('#programs').find(':selected').data('participant-required');

        if (_$country_id) {

            emptyAmountFields();

            $('.other-input-box').addClass('display-hide')
            $('#amount-group').addClass('display-hide')
            $('#participant-group').addClass('display-hide')

            $.ajax({
                    url: program_rate_route,
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        'program_id': _$program_id,
                        'country_id': _$country_id,
                        'participant_required': _$participant_required
                    },
                    beforeSend: function() {
                        App.blockUI({
                            message: 'Please wait...',
                            overlayColor: '#238650',
                        });
                    }
                })
                .done(function(response) {
                    if (response.participant == 'N') {
                        $('.rbox-wrap-amount').html(response.html);
                        if (response.other_input_box == 'Y') {
                            $('.other-input-box').removeClass('display-hide')
                        }
                        $('#amount-group').removeClass('display-hide');

                    } else if (response.participant == 'Y') {

                        $('#participant-group').removeClass('display-hide');

                        $('#participant-summary').removeClass('display-hide');

                        var _$amount = response.program_rate;

                        $('#participant-group').html('<label for="programs">Plaque <span class="required">*</span></label><div class="row">' +
                            '<div class="col col-md-7 col-sm-7 col-xs-8">' +
                            '<div class="mt-10">' +
                            '<input type="text" name="participant_name[]" id="participant_name_1" class="form-control form-control-sm" placeholder="Plaque Name">' +
                            '</div>' +
                            '</div>' +
                            '<div class="col col-md-3 col-sm-3 hidden-xs">' +
                            '<div class="input-group mt-10 ">' +
                            '<span class="input-group-addon"> <i class="fa fa-gbp"></i> </span>' +
                            '<input type="text" id="program-rate" value="' + _$amount + '" class="form-control form-control-sm" placeholder="" readonly>' +
                            '</div>' +
                            '</div>' +
                            '</div>');

                        $('#total-amount').text(_$amount)
                        $('#participant_camount').val(_$amount)
                    }

                })
                .fail(function(textStatus) {
                    console.log(textStatus)
                })
                .always(function() {
                    App.unblockUI();
                });
        }

    });
    //$("input[name='participant_name[]']").rules("add", { regex: " /^[A-Za-z0-9 ]+$/" });
    // alert('df');
    $( "input[name='participant_name[]" ).rules( "add", {
        regex: " /^[A-Za-z0-9 ]+$/",

        //minlength: 2,
        messages: {
          required: "Required input",
          minlength: jQuery.validator.format("Please, at least {0} characters are necessary")
        }

      });

    /* Add Participant Event */
    $(document).on('click', '.add-participant', function(e) {
        // var _$amount = $('.programs:checked').data('rate')
        var _$amount = $('#program-rate').val();

        _$count = $('#participant-group').data('count') + 1;

        $('#participant-group').append('<div class="row">' +
            '<div class="col col-md-7 col-sm-7 col-xs-8">' +
            '<div class="mt-10 ">' +
            '<input type="text" name="participant_name[]" id="participant_name_' + _$count + '" class="form-control form-control-sm" placeholder="Plaque Name">' +
            '</div>' +
            '</div>' +
            '<div class="col col-md-3 col-sm-3 hidden-xs">' +
            '<div class="input-group mt-10 ">' +
            '<span class="input-group-addon"> <i class="fa fa-gbp"></i> </span>' +
            '<input type="text" value="' + _$amount + '" class="form-control form-control-sm" placeholder="" readonly>' +
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

    })

    /* Delete Participant Event */
    $(document).on('click', '.delete-participant', function(e) {
        var _$el = $(this);
        var _$amount = $('#program-rate').val()
        _$total = parseFloat($('#total-amount').text()) - parseFloat(_$amount);
        // console.log(_$total)
        $('#total-amount').text(_$total.toFixed(2))
            // $('#camount').val( _$total.toFixed(2) )

        _$el.closest('.row').remove();
    })

    /* remove item from cart event */
    $(document).on('click', '.form-cart-item-remove', function() {
        var self = $(this);
        var $cart_id = self.closest('tr').find('td.form-cart-item-remove .cart_id').val();

        App.blockUI({
            message: 'Please wait...',
            overlayColor: '#238650',
        });

        $.post(delete_item, { 'cart_id': $cart_id }, function(data, textStatus, xhr) {
            if (data.success) {
                $('.one_off_total').html('<i class="fa fa-gbp"></i> ' + data.one_off_total)
                $('.monthly_total').html('<i class="fa fa-gbp"></i> ' + data.monthly_total)
                self.closest('tr').remove();
                window.location.hash = '#cart-section';
                window.location.reload()
            }
            App.unblockUI();
        }, 'json');
    })
});

var Welcome = function() {
    var donationFormValidate = function() {

        var form = $('#donation-form');
        var error = $('.form-message', form);
        $("input[name='participant_name[]']").rules("add", { regex: " /^[A-Za-z0-9/-/s._-]+$/" });
        // var regex = /^[A-Za-z0-9 ]+$/;
        // alert(regex);
        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: ":hidden", // validate all fields exluding form hidden input
            // errorLabelContainer: ".form-message",
            // wrapper: "li",
            messages: {
                donation_type: {
                    required: "Please select donation type *"
                },
                programs: {
                    required: "Please select program *"
                },
                country: {
                    required: "Please select country *"
                },
                camount: {
                    required: "Please select amount *"
                },
                'participant_name[]': {
                    required: "Please enter participant name *",
                    // maxlength:"Please enter characters within 20",
                    lettersonly:"Please enter only characters"
                },
            },

            rules: {
                donation_type: {
                    required: true
                },
                programs: {
                    required: true
                },
                country: {
                    required: true
                },
                donation_amount: {
                    required: true
                },
                'participant_name[]': {
                    required: true,
                    // maxlength: 20,
                    lettersonly:true
                },
                camount: {
                    // required: true
                    required: function(element) {
                        if ($("#custom_amount").val().length == 0) {
                            return true;
                        } else {
                            return false;
                        }
                    }
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
                // success1.hide();
                error.hide();

                //validator.errorList contains an array of objects, where each object has properties "element" and "message".  element is the actual HTML Input.
                for (var i = 0; i < validator.errorList.length; i++) {
                    console.log(validator.errorList[i]);
                }

                //validator.errorMap is an object mapping input names -> error messages
                for (var i in validator.errorMap) {
                    console.log(i, ":", validator.errorMap[i]);
                }
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
                console.log($("#custom_amount").val().length);
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

        jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z\-\s._-]+$/i.test(value);
          }, "Letters only please");
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
                        App.blockUI({
                            message: 'Please wait...',
                            overlayColor: '#238650',
                        });
                        var jqxhr = $.post(update_quantity, { 'quantity': $quantity, 'cart_id': $cart_id }, function(data, textStatus, xhr) {
                            if (data.success) {
                                $('.one_off_total').html('<i class="fa fa-gbp"></i> ' + data.one_off_total)
                                $('.monthly_total').html('<i class="fa fa-gbp"></i> ' + data.monthly_total)
                                $input.val($quantity);
                                var $amount = self.closest('tr').find('td.form-cart-item-price .form-cart-amount').text();
                                var value = parseFloat($amount) * $input.val();
                                self.closest('tr').find('td.form-cart-item-total .form-cart-amount').html('<i class="fa fa-gbp"></i> ' + value.toFixed(2));
                            }
                        }, 'json');
                        jqxhr.always(function() {
                            App.unblockUI();
                        });
                    }
                });
                $plus.on('click', function() {
                    var $cart_id = self.closest('tr').find('td.form-cart-item-remove .cart_id').val();
                    var $quantity = ++inputVal;
                    App.blockUI({
                        message: 'Please wait...',
                        overlayColor: '#238650',
                    });
                    var jqxhr = $.post(update_quantity, { 'quantity': $quantity, 'cart_id': $cart_id }, function(data, textStatus, xhr) {
                        if (data.success) {
                            $('.one_off_total').html('<i class="fa fa-gbp"></i> ' + data.one_off_total)
                            $('.monthly_total').html('<i class="fa fa-gbp"></i> ' + data.monthly_total)
                            $input.val($quantity);
                            var $amount = self.closest('tr').find('td.form-cart-item-price .form-cart-amount').text();
                            var value = parseFloat($amount) * $input.val();
                            self.closest('tr').find('td.form-cart-item-total .form-cart-amount').html('<i class="fa fa-gbp"></i> ' + value.toFixed(2));
                        }
                    }, 'json');
                    jqxhr.always(function() {
                        App.unblockUI();
                    });
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
