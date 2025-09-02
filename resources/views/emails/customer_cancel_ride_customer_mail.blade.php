<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ride Canceled - #BK{{ $booking->id }}</title>
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
            border-bottom: 3px solid #dc3545;
        }
        .header img {
            max-width: 150px;
        }
        .header h2 {
            color: #dc3545;
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
        .btn {
            display: inline-block;
            background: #007bff;
            color: #ffffff;
            padding: 12px 18px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 15px;
        }
        .booking-id {
            background: #dc3545;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
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
        <h2>Ride Canceled</h2>
        <p>Your ride has been successfully canceled.</p>
    </div>

    <div class="content">
        <p>Dear <strong>{{ $booking->user->first_name }} {{ $booking->user->last_name }}</strong>,</p>
        <p>You have successfully canceled your ride. Below are the details of the canceled booking:</p>

        <table>
            <tr>
                <td><strong>Booking ID:</strong></td>
                <td><span class="booking-id">#BK{{ $booking->id }}</span></td>
            </tr>
            <tr>
                <td><strong>Date & Time:</strong></td>
                <td>{{ date('m/d/Y', strtotime($booking->booking_date)) }} {{ $booking->booking_time }}</td>
            </tr>
            <tr>
                <td><strong>Pickup Location:</strong></td>
                <td>{{ $booking->rideRequests->pick_up_address }}</td>
            </tr>
            <tr>
                <td><strong>Drop-off Location:</strong></td>
                <td>{{ $booking->rideRequests->drop_off_address }}</td>
            </tr>
            <tr>
                <td><strong>Estimated Fare:</strong></td>
                <td>${{ number_format($booking->subtotal, 2) }}</td>
            </tr>
        </table>

        <p>If you canceled by mistake or wish to book a new ride, you can do so using the button below:</p>

        <div style="text-align: center;">
            <a href="{{ url('/book-ride') }}" class="btn">Book a New Ride</a>
        </div>

        <p class="support">If you need any assistance, feel free to contact our support team at <a href="mailto:info@troyrides.com">info@troyrides.com</a></p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</div>

</body>
</html>
