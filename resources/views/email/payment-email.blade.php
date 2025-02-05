<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0;">
    <div
        style="max-width: 600px; margin: 20px auto; background-color: #ffffff; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: 1px solid rgba(0, 0, 0, 0.1); overflow: hidden;">
        <table style="width:100%; ">
            <tr>
                <td style="background-color: #231F22; padding: 20px; text-align: center;">
                    <a href="{{ route('home') }}">
                        <img src="https://novelinkimmigration.ca/wp-content/uploads/2020/08/novelink.png" width="180"
                            alt="novelink-logo" title="Novelink Immigration">
                    </a>
                </td>
            </tr>
        </table>
        <p style="padding: 5px; font-size: 24px; font-weight: bold; text-align: center">
            YOUR PAYMENT IS CONFIRMED
        </p>
        <div style="padding: 10px;">
            <p>Dear {{ $first_name }},</p>
            <p>We are happy to inform you that your payment has been successfully processed.</p>
            <p>Your payment confirmation number is <strong>{{$confirmation_no}}</strong>. </p>

            <h2 style="color: #333333; margin-bottom: 10px;">Payment Details</h2>
            <table style="border-collapse: collapse; width: 100%; margin: 20px 0;">
                <tr>
                    <th
                        style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd; background-color: #f4f4f4; color: #333333; font-weight: bold;">
                        Amount received</th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">{{ $amount }}</td>
                </tr>
                <tr>
                    <th
                        style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd; background-color: #f4f4f4; color: #333333; font-weight: bold;">
                        For Consultation Package(s)</th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">{{ $packages }}</td>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top:10px;">
                        If you have not booked a consultation session with us, please do so now with
                        this link
                    </td>
                </tr>
                <tr>
                    <th
                        style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd; background-color: #f4f4f4; color: #333333; font-weight: bold;">
                        Appointment Link </th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">
                        {{ $links['booking'] }}.
                        <p>Ignore if you have done so already.</p>
                    </td>
                </tr>
            </table>

            <h2 style="color: #333333; margin-bottom: 10px;">Contact Us</h2>
            <table style="border-collapse: collapse; width: 100%; margin: 20px 0;">
                <tr>
                    <th
                        style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd; background-color: #f4f4f4; color: #333333; font-weight: bold;">
                        Email</th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">
                        {{ env('REPLY_TO_ADDRESS') }}</td>
                </tr>
                <tr>
                    <th
                        style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd; background-color: #f4f4f4; color: #333333; font-weight: bold;">
                        Phone</th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">{{ env('PHONE') }}</td>
                </tr>
            </table>
            <p style="color: #333333; font-size: 14px; line-height: 1.5;">Best regards,</p>
            <p style="color: #333333; font-size: 14px; line-height: 1.5;">
                Ruth Olasupo<br />
                CEO/Founder, <br /> {{ config('app.name') }}
            </p>
        </div>
        <div style="background-color: #f1f1f1; color: #777777; text-align: center; padding: 10px; font-size: 14px;">
            <table style="width: 100%">
                <tr>
                    <td style="border-top: 1px solid #a8a8a8; padding: 10px; text-align: center;">
                        <p style="font-size: 14px; margin: 0;">
                            &copy; {{ date('Y') }} <a href="{{ route('home') }}"
                                style="text-decoration:none">{{ config('app.name') }}</a>.
                            All rights reserved.
                        </p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>
