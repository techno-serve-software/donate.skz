var Profile = function() {

    $.validator.methods.email = function(value, element) {
        return this.optional(element) || /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
    }

    var checkoutFormValidate = function() {

        var form = $('#checkout-form');
        var error = $('.form-message', form);

        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: " ", // validate all fields including form hidden input
            // errorLabelContainer: ".form-message", 
            // wrapper: "li",
            messages: {
                title: {
                    required: "Please select title *"
                },
                first_name: {
                    required: "Please enter first nam *"
                },
                last_name: {
                    required: "Please enter last name *"
                },
                country: {
                    required: "Please select country *"
                },
                address1: {
                    required: "Please enter address *"
                },
                address2: {
                    required: "Please enter address *"
                },
                postcode: {
                    required: "Please enter post code *"
                },
                city: {
                    required: "Please enter city *"
                },
                phone: {
                    required: "Please enter phone *"
                },
                email: {
                    required: "Please enter email *",
                    email: "Please enter valid email *"
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
                country: {
                    required: true
                },
                address1: {
                    required: true
                },
                address2: {
                    required: true
                },
                postcode: {
                    required: true
                },
                city: {
                    required: true
                },
                phone: {
                    required: true
                },
                email: {
                    required: true,
                    email: true
                },
                camount: {
                    required: function(element) {
                        if ($("#custom_amount").val() != '') {
                            return false;
                        } else {
                            return true;
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
                if ($('#checkout-form').valid()) {
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

    return {
        init: function() {

        },
    }
}();
jQuery(function() {
    'use strict';

    Profile.init();
});