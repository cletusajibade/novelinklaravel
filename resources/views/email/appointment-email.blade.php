<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 0;">
    <div
        style="max-width: 600px; margin: 20px auto; background-color: #ffffff; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: 1px solid rgba(0, 0, 0, 0.1); overflow: hidden;">
        <table style="width:100%; ">
            <tr>
                <td style="background-color: #231F22; padding: 20px; text-align: center;">
                    <a href="{{ route('home') }}">
                        <img src="https://novelinkimmigration.ca/uploads/2020/08/novelink.png" width="180"
                            alt="novelink-logo" title="Novelink Immigration">
                    </a>
                </td>
            </tr>
        </table>
        <p style="padding: 5px; font-size: 24px; font-weight: bold; text-align: center">
            YOUR APPOINTMENT IS CONFIRMED
        </p>
        <div style="padding: 10px;">
            <p>Dear {{ $first_name }},</p>
            <p>Welcome to {{ config('app.name') }}. Thank you for booking an appointment with us.</p>
            <p>Your confirmation number is <strong>{{$confirmation_no}}</strong>. Your appointment details are as below. Please do
                not hesitate to reach out to us with the contact details below should you have any question.</p>

            <h2 style="color: #333333; margin-bottom: 10px;">Appointment Details</h2>
            <table style="border-collapse: collapse; width: 100%; margin: 20px 0;">
                <tr>
                    <th
                        style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd; background-color: #f4f4f4; color: #333333; font-weight: bold;">
                        Date</th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">{{ $date }}</td>
                </tr>
                <tr>
                    <th
                        style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd; background-color: #f4f4f4; color: #333333; font-weight: bold;">
                        Time</th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">{{ $time }}</td>
                </tr>
                <tr>
                    <th
                        style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd; background-color: #f4f4f4; color: #333333; font-weight: bold;">
                        Meeting Link (Zoom)</th>
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">
                        {{ env('ZOOM_URL') }} <br />
                        <br /> <strong>Meeting ID:</strong> {{ env('ZOOM_ID') }}
                        <br /> <strong>Passcode:</strong> {{ env('ZOOM_PASSCODE') }}
                    </td>
                </tr>
            </table>

            <h2 style="color: #333333; margin-bottom: 10px;">Quick Links</h2>
            <p style="color: #007BFF; text-decoration: none;">
                <a href="{{ $links['reschedule'] }}" style="color: #007BFF; text-decoration: none;">Reschedule
                    Appointment</a> |
                <a href="{{ $links['cancel'] }}" style="color: #007BFF; text-decoration: none;">Cancel Appointment</a>
            </p>

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
                    <td style="text-align: left; padding: 10px; border-bottom: 1px solid #ddd;">{{ env('PHONE') }}
                    </td>
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
