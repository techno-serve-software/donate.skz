var ChangePassword = function (){

    const passwordValidate = function(){
        const form = $('#changepassword-form');
        var error = $('.form-message', form);

        form.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: true, // do not focus the last invalid input
            ignore: "", // validate all fields including form hidden input
            messages: {},

            rules: {
                new_password: {
                    required: true,
                    minlength: 8
                },
                confirm_password: {
                    required: true,
                    minlength: 8,
                    equalTo: "#new_password"
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

            submitHandler : function (form){
                error.hide();
                $.ajax({
                        url: 'update-password',
                        type: 'POST',
                        dataType: 'json',
                        data: $("#changepassword-form").serialize(),
                        beforeSend: function() {
                            App.blockUI({
                                boxed: true,
                                message: 'Processing...',
                                overlayColor: '#f00',
                                target: $('#changepassword-form')
                            });
                        }
                    })
                    .done(function(response) {
                        if (response.success) {
                            console.log(response)
                            // alert("gfghf  "+response.message);
                            var html_div = "<div class='alert alert-success'>";
                            html_div += response.message;
                            html_div += "</div>";

                            $('#error-msg').html(html_div);
                            $(window).scrollTop(0);
                            // location.reload();
                            $('#changepassword-form').trigger('reset');
                        } else {
                            console.log(response)
                            var html_div = "<div class='alert alert-danger'>";
                            html_div += response.message;
                            html_div += "</div>";

                            $('#error-msg').html(html_div);
                            // error.html('<i class="fa fa-info"></i> Some error.').show();
                            $('#changepassword-form').trigger('reset');
                        }
                    })
                    .fail(function() {
                        // console.log("error");
                    })
                    .always(function() {
                        App.unblockUI($('#changepassword-form'));
                    });
            }
            
        });
    }
    $(document).ready(function(){  
        $('.pass-correct').hide();
        $('.pass-wrong').hide();
        $(document).on('keypress', '#confirm_password', function(event){
                        
            $("#error_message").html("Fields don't match");
            
            $("input").keyup(function(){
                var newpass = $("#new_password").val();
                var confirmPass = $("#confirm_password").val();
                
                // newpass = newPassword;
                // confirmPass = confirmPassword;
                changeconfirmpassword();
              });
        });
        
        var changeconfirmpassword = function(){
            var newPassword = $('#new_password').val();
            var confirmPassword = $('#confirm_password').val();
            //  console.log(newPassword + ' / ' + confirmPassword);
              if(newPassword == confirmPassword ){
                //   console.log("Testing")
                  $('.pass-wrong').hide();
                  $('.pass-correct').show().fadeIn(300).delay(1500);
              }
              else{
                  $('.pass-correct').hide();
                  $('.pass-wrong').show().fadeIn(300).delay(1500);
              }
          }
    })
    return {
        init: function() {
            passwordValidate();
        },
    }
}();

ChangePassword.init();