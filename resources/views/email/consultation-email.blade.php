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
                                Dear {{ $name }},
                            </p>
                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">
                                Welcome to {{ $app_name }}, and thanks for booking a consultation session
                                with us.
                            </p>

                             {{-- <p style="color: #333333; font-size: 14px; line-height: 1.5;">
                                 Package: {{ $package }}<br/>
                                 Amount: {{{{ $amount }}}}
                            </p> --}}

                            <p style="color: #333333; font-size: 14px; line-height: 1.5;">
                                Please join the Zoom meeting by clicking on the link:<br />
                                <a href="{{ env('ZOOM_URL') }}">{{ env('ZOOM_URL') }}</a>
                                <br /><br />
                                Or use the login details below to join on your Zoom device:
                                <br /> <strong>Meeting ID:</strong> {{ env('ZOOM_ID') }}
                                <br /> <strong>Passcode:</strong> {{ env('ZOOM_PASSCODE') }}
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


{{-- <!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>
    <!--[if mso]>
  <noscript>
    <xml>
      <o:OfficeDocumentSettings>
        <o:PixelsPerInch>96</o:PixelsPerInch>
      </o:OfficeDocumentSettings>
    </xml>
  </noscript>
  <![endif]-->
    <style>
        table,
        td,
        div,
        h1,
        p {
            font-family: Arial, sans-serif;
        }
    </style>
</head>

<body style="margin:0;padding:0;">
    <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
        <tr>
            <td align="center" style="padding:0;">
                <table role="presentation"
                    style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                    <tr>
                        <td align="center" style="padding:50px 0 50px 0;background:#70bbd9;">
                            <img src="http://dev.novelinkimmigration.ca/wp-content/uploads/2020/08/novelink.png"
                                alt="" width="300" style="height:auto;display:block;" />
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:36px 30px 42px 30px;">
                            <table role="presentation"
                                style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                <tr>
                                    <td style="padding:0 0 36px 0;color:#153643;">
                                        <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">Your
                                            Booking is Confirmed</h1>
                                        <p
                                            style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                                            Hi {{ $name }}, your booking is now confirmed.</p>
                                        <p
                                            style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                                            Please keep this email for your record. Below are the packages booked and
                                            the amount paid.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:0;">
                                        <table role="presentation"
                                            style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                                            <tr>
                                                <td style="width:260px;padding:0;vertical-align:top;color:#153643;">
                                                    <p
                                                        style="margin:0 0 25px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                                                        <img src="https://assets.codepen.io/210284/left.gif"
                                                            alt="" width="260"
                                                            style="height:auto;display:block;" />
                                                    </p>
                                                    <p
                                                        style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                                                        <b>Package(s) Selected:
                                                    </p>
                                                    <table>
                                                        <tr>
                                                            <td
                                                                style="margin:0 0 12px 0;font-size:13px;line-height:24px;font-family:Arial,sans-serif;">
                                                                {{ $package }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="margin:0 0 12px 0;font-size:13px;line-height:24px;font-family:Arial,sans-serif;">
                                                                &nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                            <td
                                                                style="margin:0 0 12px 0;font-size:13px;line-height:24px;font-family:Arial,sans-serif;">
                                                                {{ $amount }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td style="width:20px;padding:0;font-size:0;line-height:0;">&nbsp;</td>
                                                <td style="width:260px;padding:0;vertical-align:top;color:#153643;">
                                                    <p
                                                        style="margin:0 0 25px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                                                        <img src="https://assets.codepen.io/210284/right.gif"
                                                            alt="" width="260"
                                                            style="height:auto;display:block;" />
                                                    </p>
                                                    <p
                                                        style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">
                                                        <b>Appointment Schedule
                                                    </p>
                                                    <!--                          <table>-->
                                                    <!--                            <tr>-->
                                                    <!--                              <td style="margin:0 0 12px 0;font-size:13px;line-height:24px;font-family:Arial,sans-serif;">--><?php //echo $appointment_date
                                                    ?><!--</td>-->
                                                    <!--                            </tr>-->
                                                    <!--                            <tr>-->
                                                    <!--                              <td style="margin:0 0 12px 0;font-size:13px;line-height:24px;font-family:Arial,sans-serif;">--><?php //echo $appointment_time
                                                    ?><!--</td>-->
                                                    <!--                            </tr>-->
                                                    <!--                          </table>-->
                                                    <p
                                                        style="margin:0;font-size:13px;line-height:24px;font-family:Arial,sans-serif;">
                                                        <a href="{{ $zoom_link }}"
                                                            style="color:#ee4c50;text-decoration:underline;">Click
                                                            here</a> to join Zoom meeting when it's time.
                                                        We will reach out to you shortly with a list of different time
                                                        schedules you can choose from for our consultation session.

                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px;background:#ee4c50;">
                            <table role="presentation"
                                style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                                <tr>
                                    <td style="padding:0;width:50%;" align="left">
                                        <p
                                            style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                                            <a href="https://novelinkimmigration.ca/"
                                                style="color:#ffffff;text-decoration:underline;">Novelink
                                                Immigration</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html> --}}
