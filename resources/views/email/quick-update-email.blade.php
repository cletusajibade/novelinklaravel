<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ $app_name }}</title>
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
                                This is a quick update from us.
                            </p>

                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">
                                {{ $email_message }}
                            </p>

                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">If you have any question or
                                need assistance, please do not hesitate to reach out to us
                                at <a href="mailto:{{ env('REPLY_TO_ADDRESS') }}">{{ env('REPLY_TO_ADDRESS') }}</a>.
                            </p>
                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">We are here to assist you with
                                your Canada immigration plans.</p>

                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">Best regards,</p>
                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">
                                Ruth Olasupo<br />
                                CEO/Founder, <br /> {{ $app_name }}
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="border-top: 1px solid #a8a8a8; padding: 10px; text-align: center;">
                            <p style="font-size: 14px; margin: 0;">
                                &copy; {{ date('Y') }} <a href="{{ route('home') }}"
                                    style="text-decoration:none">{{ $app_name }}</a>.
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
