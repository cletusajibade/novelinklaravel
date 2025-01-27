<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">
    <!-- Wrapper Table -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0; padding: 20px; background-color: #f4f4f4;">
        <tr>
            <td align="center">
                <!-- Content Table -->
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 8px; overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #231F22; padding: 20px; text-align: center;">
                            <a href="{{ route('home') }}">
                                <img src="https://novelinkimmigration.ca/wp-content/uploads/2020/08/novelink.png"
                                    width="180" alt="novelink-logo" title="Novelink Immigration">
                            </a>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 20px;">
                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">
                                Dear {{ $first_name }},
                            </p>
                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">
                                Welcome to {{ config('app.name') }}, and thanks for booking a consultation session
                                with us. Below are the details of your pending appointment.
                            </p>

                            {{-- <p style="color: #333333; font-size: 14px; line-height: 1.5;">
                                 Package: {{ $package }}<br/>
                                 Amount: {{{{ $amount }}}}
                            </p> --}}

                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">
                                <span style="font-weight:bold; text-decoration:underline">Meeting
                                    Information</span><br />
                                <strong>Date:</strong> {{ $date }}
                                <br />
                                <strong>Time:</strong> {{ $time }}
                                <br />
                                <strong>Zoom Link:</strong> <a href="{{ env('ZOOM_URL') }}">{{ env('ZOOM_URL') }}</a>
                                <br />
                                Or use details below to join on your Zoom device:
                                <br /> <strong>Meeting ID:</strong> {{ env('ZOOM_ID') }}
                                <br /> <strong>Passcode:</strong> {{ env('ZOOM_PASSCODE') }}
                            </p>

                            <ul>
                                <li><span style="color: red">Note:</span> 1. Please, monitor your email for appointment confirmation from us. We will get back to you as soon as possible.</li>
                                <li><span style="color: red">Note:</span> 2. Also note the time/timezone of your appointment. This is your local time. If you have issues with timezone adjustments, please reach out to us.</li>
                            </ul>


                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">In the meantime, if you have any question or
                                need assistance, please do not hesitate to reach out to us
                                at <a href="mailto:{{ env('REPLY_TO_ADDRESS') }}">{{ env('REPLY_TO_ADDRESS') }}</a>, or by phone: <a href="tel:{{ env('PHONE') }}">{{ env('PHONE') }}</a>.
                            </p>
                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">We are here to assist you with
                                your Canada immigration plans.</p>

                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">Best regards,</p>
                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">
                                Ruth Olasupo<br />
                                CEO/Founder, <br /> {{ config('app.name') }}
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="border-top: 1px solid #a8a8a8; padding: 10px; text-align: center;">
                            <p style="font-size: 14px; margin: 0;">
                                &copy; {{ date('Y') }} <a href="{{ route('home') }}"
                                    style="text-decoration:none">{{ config('app.name') }}</a>.
                                All
                                rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
                <!-- End Content Table -->
            </td>
        </tr>
    </table>
    <!-- End Wrapper Table -->

</body>

</html>
