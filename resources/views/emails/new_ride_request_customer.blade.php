<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ride Request Confirmation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
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
        .details {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        .btn {
            display: inline-block;
            background: #007bff;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 6px;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="{{ url('assets/images/footer-logo.png') }}" alt="{{ config('app.name') }}">
        <h2>Ride Request Received</h2>
        <p>Thank you for booking with us!</p>
    </div>

    <div class="content">
        <p>Dear <strong>{{ $ride['customer_name'] }}</strong>,</p>
        <p>Your ride request has been received. Here are your booking details:</p>

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

        <p>We will notify you once a driver has been assigned. You can check your ride details using the button below:</p>

        <div style="text-align: center;">
            <a href="{{ url('/my-trips') }}" class="btn">View My Ride</a>
        </div>

        <p class="footer">For any assistance, contact our support at <a href="mailto:info@troyrides.com">info@troyrides.com</a></p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</div>

</body>
</html>
