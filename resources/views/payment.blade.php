@extends('inc.app')

@section('body')
<form class="credit-card" id="payment-form">
    <div class="form-header">
        <h4 class="title">Credit card detail</h4>
    </div>

    <div class="form-body">
        <!-- Card Number (Stripe Element) -->
        <div id="card-element">
            <!-- A Stripe Element will be inserted here. -->
        </div>

        <!-- Date Field (Month/Year) -->
        <div class="date-field">
            <div class="month">
                <select name="Month" id="month" required>
                    <option value="january">January</option>
                    <option value="february">February</option>
                    <option value="march">March</option>
                    <option value="april">April</option>
                    <option value="may">May</option>
                    <option value="june">June</option>
                    <option value="july">July</option>
                    <option value="august">August</option>
                    <option value="september">September</option>
                    <option value="october">October</option>
                    <option value="november">November</option>
                    <option value="december">December</option>
                </select>
            </div>
            <div class="year">
                <select name="Year" id="year" required>
                    <option value="january">2025</option>
                    <option value="february">2026</option>
                    <option value="march">2027</option>
                </select>
            </div>
        </div>

        <!-- Card Verification Field (CVV) -->
        <div class="card-verification">
            <div class="cvv-input">
                <input type="text" id="cvv" placeholder="CVV" required>
            </div>
        </div>

        <!-- Hidden amount input -->
        <input type="hidden" id="amount" value="500.0" required>

        <!-- Buttons -->
        <button type="submit" class="proceed-btn">Proceed</button>
        <button type="button" id="stripe-button" class="paypal-btn">Pay With Stripe</button>
    </div>
</form>

<script src="https://js.stripe.com/v3/"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Initialize Stripe.js and Elements
    const stripe = Stripe('pk_test_51QLYPS066VgTt731kZMY4MQfpbpilnzfgJLKPFIvhUVYHPgj69tGktS19c3obAXQR1hoDa2SvJz4Wf3qpePMX3Ws00YEoYy7M6'); // Replace with your public Stripe key
    const elements = stripe.elements();

    // Create a Card Element and mount it in the form
    const card = elements.create('card');
    card.mount('#card-element');

    document.getElementById('payment-form').addEventListener('submit', async (event) => {
        event.preventDefault(); // Prevent the form from refreshing the page

        // Get the amount value from the hidden field
        const amount = parseFloat(document.getElementById('amount').value) * 100; // Convert to cents

        // Create payment method with the card details
        const { paymentMethod, error } = await stripe.createPaymentMethod({
            type: 'card',
            card: card,
        });

        if (error) {
            alert(`Error creating payment method: ${error.message}`);
            return;
        }

        // Pass the paymentMethod.id and amount to handlePayment
        handlePayment(paymentMethod.id, amount);
    });

    // Handle the payment submission
    const handlePayment = async (paymentMethodId, amountInCents) => {
        try {
            const { data } = await axios.post('/process-payment', {
                payment_method_id: paymentMethodId,
                amount: amountInCents,
            });

            if (data.requires_action) {
                // Handle additional authentication (e.g., 3D Secure)
                const { error: actionError } = await stripe.handleCardAction(data.payment_intent_id);

                if (actionError) {
                    alert('Payment authentication failed. Please try again.');
                    return;
                }

                // Confirm payment on the backend after successful authentication
                const confirmResponse = await axios.post('/confirm-payment', {
                    payment_intent_id: data.payment_intent_id,
                });

                if (confirmResponse.data.success) {
                    alert('Payment successful!');
                } else {
                    alert('Payment confirmation failed: ' + confirmResponse.data.message);
                }
            } else if (data.success) {
                alert('Payment successful!');
            } else {
                alert('Payment failed: ' + data.message);
            }
        } catch (error) {
            console.error(error);
            alert('An error occurred. Please try again.');
        }
    };
</script>

@endsection
