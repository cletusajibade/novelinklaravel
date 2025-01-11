 <form id="payment-form" action="{{route('stripe.pay')}}" method="post">
     @csrf
     <div id="payment-element">
         <!--Stripe.js injects the Payment Element-->
     </div>
     <button id="submit">
         <div class="spinner hidden" id="spinner"></div>
         <span id="button-text">Pay now</span>
     </button>
     <div id="payment-message" class="hidden"></div>
 </form>
