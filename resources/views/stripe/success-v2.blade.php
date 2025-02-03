<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .success-container {
            max-width: 600px;
            background: #fff;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .success-header {
            font-size: 24px;
            color: #28a745;
            margin-bottom: 10px;
        }
        .success-message {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }
        .button-container {
            display: flex;
            justify-content: center;
        }
        .book-link {
            display: inline-block;
            padding: 10px 20px;
            font-size: 14px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            background-color: #007bff;
            transition: background-color 0.3s;
        }
        .book-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-header">Payment Successful</div>
        <div class="success-message">Your payment has been processed successfully. You can now proceed to book an appointment or check your email to do so later.</div>
        <div class="button-container">
            <a href="{{route('appointment.create')}}" class="book-link">Book Appointment Now</a>
        </div>
    </div>
    @include('includes.disable-browser-back-button')
</body>
</html>
