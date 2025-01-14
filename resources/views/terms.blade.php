<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500&display=swap">
    <link rel="stylesheet" href="{{ asset('css/agreement.css') }}" />
    <title>Novelink: Consultation Agreement</title>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="header__box">
                <div class="header__logobox">
                    <div class="header__logo">
                        <img src="{{ asset('uploads/2020/08/novelink.png') }}" class="custom-logo" alt="Novelink"
                            width="200">
                    </div>
                    <div class="header__reg"></div>
                </div>
                <div class="header__rcic">
                    <img src="{{ asset('uploads/2020/08/RCIC_EN_HORZ_CLR_POS_TM.png') }}" alt="R712188"
                        style="width: 200px; display: block; margin-left: auto; margin-right: auto;">
                </div>
            </div>
        </div>
        <div class="main-body">
            <div class="main-body__title">
                <h2>Initial Consultation Agreement </h2>
            </div>
            <div class="main-body__content">
                <p>
                    This initial Consultation Agreement is made this day between Regulated
                    Canadian Immigration Consultant (RCIC) <strong>Ruth Olasupo</strong> of <strong>Novelink
                        Immigration</strong> and the undersigned Client (“you”), for the purpose of setting
                    up our initial Canadian immigration guidance and consultation session to
                    discuss Client's eligibility and Canadian immigration procedures as
                    applicable to the Client.
                </p>
                <p>The Client agrees to the following:</p>
                <ul>
                    <li>The Client is obliged to pay the associated fee to the consultant for the
                        said service.
                    </li>
                    <li>The Client agrees that this initial consultation with the RCIC does not
                        constitute any form of agreement that empowers the RCIC member to
                        act as an authorized representative to the Client or submit applications
                        on their behalf.
                    </li>
                    <li>This payment is for a one-time consultation session and does not constitute full representation
                        for
                        an immigration application.
                    </li>
                    <li>The fee is non-refundable after the session.</li>
                    <li>Should you request a cancellation of the session you are required to provide at least 24hour
                        notice
                        before the time scheduled for the session.
                    </li>
                    <li>A refund can only be issued when the appropriate cancellation notice has been given and the
                        amount
                        refunded would be less 10% of the amount paid.
                    </li>
                </ul>
                <p>
                    This Agreement shall be governed by the laws in effect in the Province of
                    Alberta and the federal laws of Canada.
                </p>
                <p>
                    Please be advised that the RCIC is a member in good standing of the College
                    of Immigration and Citizenship Consultants (CICC), and as such, is bound by
                    its By-laws, Code of Professional Ethics, and associated Regulations.
                </p>
            </div>

        </div>
        <div class="footer">
            <hr />
            <div class="footer__slogan">
                <span>We make your immigration dreams a reality</span>
            </div>
            <!--            <dev class="footer-box">-->
            <!--                <div class="footer__left">-->
            <!--                    <span><i class="fa-regular fa-envelope"></i>&nbsp;info@novelinkimmigration.ca</span>-->
            <!--                    <span><i class="fa-brands fa-instagram"></i>&nbsp;novelinkimmigration</span>-->
            <!--                </div>-->
            <!--                <div class="footer__right">-->
            <!--                    <span><i class="fas fa-phone-volume"></i>&nbsp;+1 587 707 4206</span>-->
            <!--                    <span><i class="fa-brands fa-whatsapp"></i>&nbsp;+1 587 707 4206</span>-->
            <!--                </div>-->
            <!--            </dev>-->
            <div class="footer__form">
                <!-- <form action="<?php //echo htmlspecialchars($_SERVER["PHP_SELF"]);
                ?>" method="post"> -->
                <form action="{{ route('consultation.post-terms') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="row__left">
                            <span class="spacer check--cursor">
                                <input type="checkbox" id="check_agree" name="check_agree" value="1" {{ old('check_agree') ? 'checked' : '' }}
                                    class="check--cursor">
                                <label for="check_agree" class="check--cursor"> I accept the terms of this
                                    agreement</label>
                            </span>

                            <input type="text" name="agree_name" placeholder="Enter your name here" size="40"
                                value="{{ old('agree_name', $full_name) }}">
                            <!-- Note: $full_name is from the ConsultationController -->
                        </div>

                        <div class="row__right">
                            <button class="btn" type="submit" name="submit_agreement"> Proceed to Payment</button>
                        </div>
                    </div>
                    <div id="error-div">
                        @error('check_agree')
                            <div class="error-message" style="color: red">{{ $message }}</div>
                        @enderror
                    </div>
                    <div id="error-div">
                        @error('agree_name')
                            <div class="error-message" style="color: red">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // scroll down to the alert
        document.addEventListener('DOMContentLoaded', function() {
            const firstError = document.querySelector('.error-message');
            if (firstError) {
                firstError.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }
        });
    </script>
</body>

</html>
