Setting up Stripe in a Laravel 11 application involves the following steps:

---

### **Step 1: Install the Stripe PHP SDK**
Run the following command to install the official Stripe PHP library via Composer:
```bash
composer require stripe/stripe-php
```

---

### **Step 2: Configure Stripe API Keys**
1. Add your **Stripe API keys** (test and live) to the `.env` file:
   ```dotenv
   STRIPE_KEY=sk_test_your_secret_key
   STRIPE_PUBLIC_KEY=pk_test_your_public_key
   ```

2. Access these keys in your application by adding them to `config/services.php`:
   ```php
   'stripe' => [
       'secret' => env('STRIPE_KEY'),
       'public' => env('STRIPE_PUBLIC_KEY'),
   ],
   ```

---

### **Step 3: Create a Payment Controller**
Generate a controller to handle payment logic:
```bash
php artisan make:controller PaymentController
```

#### **Example Code for `PaymentController`:**
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        // Set your secret key
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Create a PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $request->amount, // Amount in cents
                'currency' => 'usd',
                'metadata' => ['order_id' => $request->order_id],
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
```

---

### **Step 4: Create Payment Routes**
Define the routes in `routes/web.php` or `routes/api.php`:
```php
use App\Http\Controllers\PaymentController;

Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
```

---

### **Step 5: Frontend Integration**
You can use Stripe's JavaScript library to handle the payment UI.

1. Include Stripe.js in your HTML:
   ```html
   <script src="https://js.stripe.com/v3/"></script>
   ```

2. Create a payment form:
   ```html
   <form id="payment-form">
       <div id="card-element"><!-- Stripe Elements will create the form here --></div>
       <button type="submit">Pay</button>
   </form>
   <div id="payment-result"></div>
   ```

3. Add JavaScript to handle Stripe Elements:
   ```javascript
   document.addEventListener('DOMContentLoaded', function () {
       const stripe = Stripe('{{ config('services.stripe.public') }}');
       const elements = stripe.elements();
       const card = elements.create('card');
       card.mount('#card-element');

       const form = document.getElementById('payment-form');
       form.addEventListener('submit', async (event) => {
           event.preventDefault();

           const { paymentIntent, error } = await stripe.confirmCardPayment(
               '{{ clientSecret }}', // Obtain from the backend
               {
                   payment_method: {
                       card: card,
                   },
               }
           );

           if (error) {
               document.getElementById('payment-result').textContent = error.message;
           } else {
               document.getElementById('payment-result').textContent =
                   'Payment successful!';
           }
       });
   });
   ```

---

### **Step 6: Test the Payment**
1. Use Stripe's [test card numbers](https://stripe.com/docs/testing) to simulate payments.
2. Make sure your API endpoint works, and the payment form displays correctly.

---

### **Step 7: Handle Webhooks (Optional)**
To synchronize payment statuses, set up a webhook in Stripe to listen for events like `payment_intent.succeeded`:
1. Create a webhook controller:
   ```bash
   php artisan make:controller WebhookController
   ```
   
2. Example webhook handler:
   ```php
   namespace App\Http\Controllers;

   use Illuminate\Http\Request;

   class WebhookController extends Controller
   {
       public function handleStripeWebhook(Request $request)
       {
           $payload = $request->all();

           if ($payload['type'] === 'payment_intent.succeeded') {
               $paymentIntent = $payload['data']['object'];
               // Handle successful payment intent
           }

           return response()->json(['status' => 'success']);
       }
   }
   ```

3. Add a route for the webhook:
   ```php
   Route::post('/webhook/stripe', [WebhookController::class, 'handleStripeWebhook']);
   ```

4. Configure the webhook URL in your Stripe dashboard.

---

### Additional Tips
- Use **middleware** to secure sensitive routes.
- Log Stripe events for debugging.
- Test in **test mode** thoroughly before switching to live mode.

Would you like a detailed frontend example or help with testing?
