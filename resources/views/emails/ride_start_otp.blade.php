<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OTP for Ride Verification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 3px solid #007bff;
        }
        .header img {
            max-width: 150px;
        }
        .header h2 {
            color: #007bff;
            margin-top: 10px;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .otp-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #f1f1f1;
            border-radius: 8px;
        }
        .otp-section h4 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }
        .otp-code {
            font-size: 18px;
            font-weight: bold;
            color: #007bff;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 6px;
            text-align: center;
        }
        .support {
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="{{ url('assets/images/footer-logo.png') }}" alt="{{ config('app.name') }}" style="max-width: 150px;">
        <h2>OTP for Ride Verification</h2>
        <p>Your ride has been confirmed!</p>
    </div>

    <div class="content">
        <p>Dear Customer,</p>
        <p>Thank you for booking with <strong>{{ config('app.name') }}</strong>. Below is your OTP for ride verification:</p>
         
        <div class="otp-section">
            <h4>OTP Code:</h4>
            <div class="otp-code">{{ $otp }}</div>
            <p>Please share this OTP with your driver when they start the ride to verify your journey.</p>
        </div>

        <div class="details">
            <table>
                <tr>
                    <td><strong>Pickup Address:</strong></td>
                    <td>{{ $ride['pick_up_address'] }}</td>
                </tr>
                <tr>
                    <td><strong>Drop-off Address:</strong></td>
                    <td>{{ $ride['drop_off_address'] }}</td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td>{{ date('m/d/Y', strtotime($ride['pick_up_date'])) }}</td>
                </tr>
                <tr>
                    <td><strong>Time:</strong></td>
                    <td>{{ $ride['pick_up_time'] }}</td>
                </tr>
                <tr>
                    <td><strong>Passengers:</strong></td>
                    <td>{{ $ride['total_passenger'] }}</td>
                </tr>
                <tr>
                    <td><strong>Estimated Fare:</strong></td>
                    <td>${{ number_format($ride['subtotal'], 2) }}</td>
                </tr>
            </table>
        </div>

        <p class="support">For any inquiries, contact our support at <a href="mailto:info@troyrides.com">info@troyrides.com</a></p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</div>

</body>
</html>
