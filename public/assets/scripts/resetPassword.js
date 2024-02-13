var ResetPassword = function() {

    var resetPasswordValidate = function() {

        var form = $('#resetPassword-form');
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

                user_password: {
                    required: true
                },
                confirm_password: {
                    required: true,
                    equalTo: "#user_password"
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
                var _$el = $('#resetPassword-form');
                var dataString = _$el.serialize();
                var _$elSubmit = _$el.find('[type="submit"]');
                _$el.find('.loader').remove();
                _$elSubmit.after('<div class="loader"></div>');

                $.ajax({
                        url: reset_password_route,
                        type: 'POST',
                        dataType: 'json',
                        data: dataString,
                        beforeSend: function() {
                            App.blockUI({
                                boxed: true,
                                message: 'Processing...',
                                overlayColor: '#f00',
                                target: $('#resetPassword-form')
                            });
                        }
                    })
                    .done(function(response) {
                        if (response.success) {
                            var html_div = "<div class='alert alert-success'>";
                            html_div += response.message;
                            html_div += "</div>"
                            $('#error-msg').html(html_div);

                        } else {
                            // prepare error-msg-alert div
                            var html_div = "<div class='alert alert-danger'>";
                            html_div += response.message;
                            html_div += "</div>";

                            $('#error-msg').html(html_div);

                        }

                        _$el.find('.loader').remove();
                        _$el.find('.alert').fadeIn();
                        setTimeout(function() {
                            _$el.find('.alert').fadeOut(function() {
                                $(this).remove();
                            });
                        }, 5000);
                    })
                    .fail(function(data) {
                        console.log("error1");
                    })
                    .always(function() {
                        App.unblockUI($('#resetPassword-form'));
                    });

            }
        });
    }

    return {
        init: function() {
            resetPasswordValidate();
        },
    };
}();

ResetPassword.init();