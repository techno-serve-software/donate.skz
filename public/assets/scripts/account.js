

$(document).ready(function() {
// alert('gfgf');
    $(document).on('click', '.nav-item', function(event) {
        if (!($('#payment_details_div').hasClass('hide'))) {
            $('#payment_details_div').addClass('hide');
        }
    });
    $(document).on('click', '.dd_payment_detail', function(event) {
        // alert('fdfdgfgfgfgffg');
        var direct_debit_ref = $(this).attr('value');
        $.ajax({
                url: 'ddPaymentDetails',
                type: 'POST',
                dataType: 'html',
                data: { "direct_debit_ref": direct_debit_ref },
                beforeSend: function() {
                    App.blockUI({
                        boxed: true,
                        message: 'Processing...',
                        overlayColor: '#f00',
                        target: $('#payment_details_div')
                    });
                }
            })
            .done(function(data) {
                $('.payment-details-body').html(data);
                $('.btn-popup').click();
            })
            .fail(function() {
                console.log("error");
            })
            .always(function() {
                App.unblockUI($('#payment_details_div'));
            });
        /*** Add smooth scrolling to all dd_id ***/
        // Store hash
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.substr(1) + ']');
        if (target.length) {

            // Using jQuery's animate() method to add smooth page scroll
            // The optional number (1000) specifies the number of milliseconds it takes to scroll to the specified area
            $('html,body').animate({
                scrollTop: target.offset().top - 30
            }, 1000);
            return false;
        }
    });

    $(document).on('change', '#order_by', function(event) {
        var sortValue=$('#sort_value').val();
        // var pageURL = $(location).attr("href");
        pageURL=(window.location.href.split('?')[0]+ '?page='+ JsVariables.current_page);
        var newUrl=pageURL+"&orderby=" + $(this).val() + "&" +"sortValue=" + sortValue;
        window.location.href = newUrl;
    });

    $(document).on('change', '#sort_value', function(event) {
        var order_by=$('#order_by').val();
        // var pageURL = $(location).attr("href");
        pageURL=(window.location.href.split('?')[0]+ '?page='+ JsVariables.current_page);
        var newUrl=pageURL+"&sort_value=" + $(this).val() + "&" +"order_by=" + order_by;
        window.location.href = newUrl;
    });

     $(document).on('change', '#dd_order_by', function(event) {
        var sortValue=$('#dd_sort_value').val();
        // var pageURL = $(location).attr("href");
        pageURL=(window.location.href.split('?')[0]+ '?page='+ JsVariables.current_page);
        var newUrl=pageURL+"&orderby=" + $(this).val() + "&" +"sortValue=" + sortValue;
        window.location.href = newUrl;
    });

    $(document).on('change', '#dd_sort_value', function(event) {
        var order_by=$('#dd_order_by').val();
        // var pageURL = $(location).attr("href");
        pageURL=(window.location.href.split('?')[0]+ '?page='+ JsVariables.current_page);
        var newUrl=pageURL+"&sort_value=" + $(this).val() + "&" +"order_by=" + order_by;
        window.location.href = newUrl;
    });
});