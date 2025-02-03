<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Novelink Stripe payment processing" />
    <title>Novelink Booking</title>

    <!-- Load Bundled Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Load Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>

    <style>
        /* Hide the payment form after a successfull payment */
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div class="flex items-center justify-center">
        <div class="bg-[#231f20] w-[30%] p-6 flex items-center justify-between">
            <a href="{{ route('home') }}"> <img src="{{ asset('uploads/2020/08/novelink.png') }}" alt="Logo"
                    width="120" /> </a>
            <h2 class="text-white text-lg">Complete your payment</h2>
        </div>
    </div>

    <div class="mx-auto max-w-[30%] rounded-lg p-2 text-center">
        {{-- @if ($errors->has('error_pending_or_confirmed_appointment'))
            <div class="alert alert-danger">
                <x-bladewind::alert type="error" shade="dark">
                    {{ $errors->first('error_pending_or_confirmed_appointment') }}
                </x-bladewind::alert>
            </div>
        @endif --}}

        {{-- @if (session('success'))
            <div id="success-message">
                <x-alert type="success" :message="session('success')" />
                <!-- Go to the appointment booking calendar -->
                <form action="{{ route('appointment.create') }}">
                    @csrf
                    <p class="pb-4 pt-6">Click the button below to book your appointment now, or use the link in your
                        email to do so later.</p>
                    <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                        Book Appointment
                    </button>
                </form>
            </div>
        @endif --}}


        <!-- This form inflates/loads the Stripe Payment Element  -->
        <form id="payment-form">
            @csrf
            <div id="payment-element">
                <!-- Elements will create form elements here -->
            </div>
            <div id="address-element">
                <!-- Elements will create form elements here -->
            </div>
            <hr style="margin-top: 25px;">
            @isset($total_consultation_fee)
                <div style="display: flex; justify-content: space-between; padding: 10px 0">
                    <span class="amount">Total Amount:</span>
                    <span class="amount">{{ session('currency') ?? 'CAD' }} ${{ $total_consultation_fee / 100 }} </span>
                </div>
            @endisset

            <button id="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow-md">
                Make Payment
            </button>

            <div id="error-message" style="color: red">
                <!-- Display error message to your customers here -->
            </div>
        </form>
    </div>

    <script>
        const form = document.getElementById('payment-form');

        if (document.getElementById('success-message')) {
            form.classList.add('hidden');
        }
        /**
         * 1. Create an instance of Stripe on the client side
         *  - Set your publishable key: remember to change this to your live publishable key in production
         *  - See your keys here: https://dashboard.stripe.com/apikeys
         **/
        const stripe = Stripe(@json($pub_key));

        const options = {
            clientSecret: @json($intent->client_secret),
            // Fully customizable with appearance API.
            appearance: {
                theme: 'stripe',
                variables: {
                    colorPrimary: '#0570de',
                    colorBackground: '#ffffff',
                    colorText: '#30313d',
                    colorDanger: '#df1b41',
                    fontFamily: 'Ideal Sans, system-ui, sans-serif',
                    // spacingUnit: '2px',
                    borderRadius: '3px',
                }
            },
        };

        /*
         * 2. Set up Stripe.js and Elements, passing the client secret obtained in the previous step
         */
        const elements = stripe.elements(options);

        const paymentOptions = {
            layout: {
                type: 'accordion',
                defaultCollapsed: false,
                radios: true,
                spacedAccordionItems: false
            }
        };

        //3. Create and mount the Payment and Address Elements on the form
        const paymentElement = elements.create('payment' /*,paymentOptions*/ );
        // const addressElement = elements.create('address', {mode: 'billing',});
        paymentElement.mount('#payment-element');
        // addressElement.mount('#address-element');

        /*
         * 4. Submit payment to Stripe
         */
        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            const {
                error
            } = await stripe.confirmPayment({
                //`Elements` instance that was used to create the Payment Element
                elements,
                confirmParams: {
                    return_url: @json($return_url),
                },
            });

            if (error) {
                // This point will only be reached if there is an immediate error when
                // confirming the payment. Show error to your customer (for example, payment
                // details incomplete)
                const messageContainer = document.querySelector('#error-message');
                messageContainer.textContent = error.message;
            } else {
                // Your customer will be redirected to your `return_url`. For some payment
                // methods like iDEAL, your customer will be redirected to an intermediate
                // site first to authorize the payment, then redirected to the `return_url`.
            }
        });
    </script>
</body>

</html>
