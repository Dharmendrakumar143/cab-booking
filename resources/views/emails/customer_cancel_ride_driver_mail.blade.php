<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ride Canceled - #BK{{ $booking->id }}</title>
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
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin: auto;
            text-align: left;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #dc3545;
            padding-bottom: 15px;
        }
        .header img {
            max-width: 140px;
        }
        .header h2 {
            color: #dc3545;
            font-size: 22px;
            margin-top: 10px;
        }
        .content {
            padding: 20px 0;
        }
        .booking-id {
            background: #dc3545;
            color: white;
            padding: 6px 14px;
            border-radius: 5px;
            font-weight: bold;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .cta-container {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            background: #007bff;
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            display: inline-block;
        }
        .support {
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <img src="{{ url('assets/images/footer-logo.png') }}" alt="{{ config('app.name') }}">
        <h2>Ride Canceled</h2>
        <p>The customer has canceled their ride.</p>
    </div>

    <div class="content">
        <p>Dear <strong>{{ $booking->admin->first_name ?? 'TroyRides' }}  {{ $booking->admin->last_name ?? Null}}</strong>,</p>
        <p>We regret to inform you that the following ride has been canceled by the customer:</p>

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

        <p>We apologize for any inconvenience. You will be notified when a new ride request is available.</p>

        <div class="cta-container">
            <a href="{{ url('/admin/rides') }}" class="btn">View Available Rides</a>
        </div>

        <p class="support">If you need assistance, contact our support team at <a href="mailto:info@troyrides.com">info@troyrides.com</a>.</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
    </div>
</div>

</body>
</html>
