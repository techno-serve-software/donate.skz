(function() {
    "use strict";

    var elements = stripe.elements({
        fonts: [{
            cssSrc: "https://rsms.me/inter/inter-ui.css"
        }],
        // Stripe's examples are localized to specific languages, but if
        // you wish to have Elements automatically detect your user's locale,
        // use `locale: 'auto'` instead.
        locale: window.__exampleLocale
    });

    /**
     * Card Element
     */
    var card = elements.create("card", {
        style: {
            base: {
                color: "#32325D",
                fontWeight: 500,
                fontFamily: "Inter UI, Open Sans, Segoe UI, sans-serif",
                fontSize: "16px",
                fontSmoothing: "antialiased",

                "::placeholder": {
                    color: "#CFD7DF"
                }
            },
            invalid: {
                color: "#E25950"
            }
        },
        hidePostalCode: true
    });

    card.mount("#techno-card");

    /**
     * Payment Request Element
     */
    var paymentRequest = stripe.paymentRequest({
        country: "US",
        currency: "gbp",
        total: {
            amount: 2000,
            label: "Total"
        }
    });
    
    paymentRequest.on("token", function(result) {
        var example = document.querySelector(".techno");
        example.querySelector(".token").innerText = result.token.id;
        example.classList.add("submitted");
        result.complete("success");
    });

    var paymentRequestElement = elements.create("paymentRequestButton", {
        paymentRequest: paymentRequest,
        style: {
            paymentRequestButton: {
                type: "donate"
            }
        }
    });

    paymentRequest.canMakePayment().then(function(result) {
        if (result) {
            document.querySelector(".techno .card-only").style.display = "none";
            document.querySelector(
                    ".techno .payment-request-available"
                ).style.display =
                "block";
            paymentRequestElement.mount("#techno-paymentRequest");
        }
    });

    registerElements([card, paymentRequestElement], "techno");
})();