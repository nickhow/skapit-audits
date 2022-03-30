

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  
    <link href="<?php echo base_url('/css/main.css') ?>" rel="stylesheet">

    <link rel="stylesheet" href="<?= site_url() ?>css/checkout.css"/>

    <script src="https://js.stripe.com/v3/" ></script>
    




<div class="container">
    <div class="container-xs pt-5">

        <div class="row justify-content-center">
        
            <form id="payment-form" style="max-width:600px; background:white; padding:20px">
                <div class="row mb-4 p-1">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <?php echo $text['payment_title']; ?>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-row justify-content-between rounded bg-light m-2 p-3">
                            <div class="d-flex align-items-center">
                                <div>
                                    <?php echo $text['payment_item']; ?>
                                </div>
        
                            </div>
                            <div class="d-flex align-items-center text-end">
                                <div class="display-5">€50.00</div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="col-8 offset-2 border-bottom mb-4 "></div>
                
                <div class="fs-4"><?php echo $text['payment_details']; ?></div>

                <label for"email"><?php echo $text['payment_receipt']; ?></label>
                <input type="text" id="email" placeholder="<?php echo $text['payment_receipt_placeholder']; ?>" />
                 <input type="hidden" name="id" id="id" value="<?php echo $audit_obj['id']; ?>">
              <div id="payment-element">
                <!--Stripe.js injects the Payment Element-->
              </div>
              <button id="submit" name="payment"> 
                <div class="spinner hidden" id="spinner"></div>
                <span id="button-text"><?php echo $text['pay_now']; ?></span>
              </button>
              <div id="payment-message" class="hidden"></div>
            </form>

        </div>
    </div>

</div>





<script>
// This is your test publishable API key.
const stripe = Stripe("<?php echo getenv('stripe.key') ?>",{
    locale: '<?php echo $audit_obj['language']; ?>'
});

// The items the customer wants to buy
const items = [{ id: "health-and-safety-audit" }];
    
let elements;
        
initialize();
checkStatus();
    
document
  .querySelector("#payment-form")
  .addEventListener("submit", handleSubmit);
        
// Fetches a payment intent and captures the client secret
async function initialize() {
   // const { clientSecret } = await fetch("<?php echo base_url(); ?>/stripe/create-charge", {
    const { clientSecret } = await fetch("https://audit.ski-api-technologies.com/stripe/create-charge", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ items }),
    }).then((r) => r.json());
        
    const appearance = {
      theme: 'flat',
        variables: {
        colorPrimary: '#01a095',
        colorBackground: '#f5f5f5',
        colorText: '#30313d',
        colorDanger: '#df1b41',
        fontFamily: 'Ideal Sans, system-ui, sans-serif',
      }

    };
        
    elements = stripe.elements({ clientSecret, appearance });
        
    const paymentElement = elements.create("payment");
    paymentElement.mount("#payment-element");
}
        
async function handleSubmit(e) {
    e.preventDefault();
    setLoading(true);
        
    const { error } = await stripe.confirmPayment({
        elements,
        confirmParams: {
            // Make sure to change this to your payment completion page
            //return_url: "<?php echo base_url(); ?>/stripe",
            return_url: "<?php echo base_url(); ?>/payment-success/<?php echo $audit_obj['id']; ?>",
            receipt_email: document.getElementById("email").value,
        },
    });
        
    // This point will only be reached if there is an immediate error when
    // confirming the payment. Otherwise, your customer will be redirected to
    // your `return_url`. For some payment methods like iDEAL, your customer will
    // be redirected to an intermediate site first to authorize the payment, then
    // redirected to the `return_url`.
    if (error.type === "card_error" || error.type === "validation_error") {
        showMessage(error.message);
    } else {
        showMessage("An unexpected error occured.");
    }
        
    setLoading(false);
}
        
// Fetches the payment intent status after payment submission
async function checkStatus() {
    const clientSecret = new URLSearchParams(window.location.search).get(
        "payment_intent_client_secret"
    );
        
    if (!clientSecret) {
        return;
    }
        
    const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);
        
    switch (paymentIntent.status) {
        case "succeeded":
            showMessage("Payment succeeded!");
            break;
        case "processing":
            showMessage("Your payment is processing.");
            break;
        case "requires_payment_method":
            showMessage("Your payment was not successful, please try again.");
            break;
        default:
            showMessage("Something went wrong.");
            break;
    }
}
        
// ------- UI helpers -------
function showMessage(messageText) {
    const messageContainer = document.querySelector("#payment-message");
        
    messageContainer.classList.remove("hidden");
    messageContainer.textContent = messageText;
        
    setTimeout(function () {
        messageContainer.classList.add("hidden");
        messageText.textContent = "";
    }, 4000);
}
        
// Show a spinner on payment submission
function setLoading(isLoading) {
    if (isLoading) {
        // Disable the button and show a spinner
        document.querySelector("#submit").disabled = true;
        document.querySelector("#spinner").classList.remove("hidden");
        document.querySelector("#button-text").classList.add("hidden");
    } else {
        document.querySelector("#submit").disabled = false;
        document.querySelector("#spinner").classList.add("hidden");
        document.querySelector("#button-text").classList.remove("hidden");
    }
}
</script>

    </body>
</html>
