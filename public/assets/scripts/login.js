var Login = function() {

    $.validator.methods.email = function(value, element) {
        return this.optional(element) || /^\w+([-+.'][^\s]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value);
    }

    // custom validation method
    jQuery.validator.addMethod("alphabetsAndSpacesOnly", function(value, element) {
        return this.optional(element) || value == value.match(/^[a-zA-Z\s]+$/);
    }, "Please enter only letters");

    var appFilePath = '';

    var loginFormValidate = function() {

        var form = $('#login');
        var error = $('.form-message', form);

        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            messages: {},

            rules: {
                user_email: {
                    required: true,
                    email: true
                },
                user_password: {
                    required: true
                }
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
                var _$el = $('#login');
                var dataString = _$el.serialize();
                var _$elSubmit = _$el.find('[type="submit"]');
                _$el.find('.loader').remove();
                _$elSubmit.after('<div class="loader"></div>');
                $.ajax({
                        url: login,
                        type: 'POST',
                        dataType: 'json',
                        data: dataString,
                        beforeSend: function() {
                            App.blockUI({
                                boxed: true,
                                message: 'Processing...',
                                overlayColor: '#f00',
                                target: $('#login-form')
                            });
                        }
                    })
                    .done(function(response) {
                        if (response.success) {

                            location.reload();

                        } else {
                            var html_div = "<div class='alert alert-danger'>";
                            html_div += response.message;
                            html_div += "</div>";
                            $('#error-msg').html(html_div);
                            $('#login').trigger('reset');

                            _$el.find('.loader').remove();
                            _$el.find('.alert').fadeIn();
                            setTimeout(function() {
                                _$el.find('.alert').fadeOut(function() {
                                    $(this).remove();
                                });
                            }, 5000);
                        }
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        App.unblockUI($('#login-form'));
                    });
            }
        });
    }

    var signupFormValidate = function() {

        var form = $('#signup');
        var error = $('.form-message', form);

        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            messages: {},

            rules: {
                first_name: {
                    required: true,
                    minlength: 2,
                    alphabetsAndSpacesOnly: true
                },
                last_name: {
                    required: true,
                    minlength: 2,
                    alphabetsAndSpacesOnly: true
                },
                user_email: {
                    required: true,
                    email: true
                },
                user_password: {
                    required: true
                }
                
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
                var _$el = $('#signup');
                var dataString = _$el.serialize();
                var _$elSubmit = _$el.find('[type="submit"]');
                _$el.find('.loader').remove();
                _$elSubmit.after('<div class="loader"></div>');
                $.ajax({
                        url: signup,
                        type: 'POST',
                        dataType: 'json',
                        data: dataString,
                        beforeSend: function() {
                            App.blockUI({
                                boxed: true,
                                message: 'Processing...',
                                overlayColor: '#f00',
                                target: $('#signup')
                            });
                        }
                    })
                    .done(function(response) {
                        if (response.success) {
                            location.assign(appFilePath + 'account/profile');
                        } else {
                            // prepare error-msg-alert div
                            var html_div = "<div class='alert alert-danger'>";
                            html_div += response.message;
                            html_div += "</div>";
                            $('#error-msg').html(html_div);

                            _$el.find('.loader').remove();
                            _$el.find('.alert').fadeIn();
                            setTimeout(function() {
                                _$el.find('.alert').fadeOut(function() {
                                    $(this).remove();
                                });
                            }, 5000);
                        }
                    })
                    .fail(function(data) {
                        console.log("error1");
                    })
                    .always(function() {
                        App.unblockUI($('#signup'));
                    });

            }
        });
    }

    var forgetFormValidate = function() {

        var form = $('#forget-form');
        var error = $('.form-message', form);

        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            messages: {
                select_multi: {
                    maxlength: jQuery.validator.format("Max {0} items allowed for selection"),
                    minlength: jQuery.validator.format("At least {0} items must be selected")
                }
            },

            rules: {

                user_email: {
                    required: true,
                    email: true
                }
            },

            invalidHandler: function(event, validator) { //display error alert on form submit              
                // success1.hide();
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
                var _$el = $('#forget-form');
                var dataString = _$el.serialize();
                var _$elSubmit = _$el.find('[type="submit"]');
                _$el.find('.loader').remove();
                _$elSubmit.after('<div class="loader"></div>');

                $.ajax({
                        url: resetToken,
                        type: 'POST',
                        dataType: 'json',
                        data: dataString,
                        beforeSend: function() {
                            App.blockUI({
                                boxed: true,
                                message: 'Processing...',
                                overlayColor: '#f00',
                                target: $('#forget-form')
                            });
                        }
                    })
                    .done(function(response) {
                        if (response.success) {
                            var html_div = "<div class='alert alert-success'>";
                            html_div += 'An email has been sent to your registered email address!'
                            html_div += "</div>"

                            $('#error-msg').html(html_div);
                            
                        } else {
                            // prepare error-msg-alert div
                            var html_div = "<div class='alert alert-danger'>";
                            html_div += response.message
                            html_div += "</div>"

                            $('#error-msg').html(html_div);

                            _$el.find('.loader').remove();
                            _$el.find('.alert').fadeIn();
                            setTimeout(function() {
                                _$el.find('.alert').fadeOut(function() {
                                    $(this).remove();
                                });
                            }, 5000);
                        }
                    })
                    .fail(function(data) {
                        console.log("error1");
                    })
                    .always(function() {
                        App.unblockUI($('#forget-form'));
                    });

            }
        });
    }

    var pageActions = function() {

    }

    return {
        init: function() {
            loginFormValidate();
            signupFormValidate();
            forgetFormValidate();
            pageActions();
        },
    };
}();

Login.init();