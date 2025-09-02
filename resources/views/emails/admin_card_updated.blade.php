<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Card Update Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .header img {
            width: 150px;
        }
        .content {
            font-size: 16px;
            color: #333333;
            line-height: 1.6;
        }
        .content table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .content td {
            padding: 8px;
            border: 1px solid #e0e0e0;
        }
        .content th {
            text-align: left;
            padding: 8px;
            background-color: #f9f9f9;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #999999;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ url('assets/images/footer-logo.png') }}" alt="{{ config('app.name') }}" style="max-width: 150px;">
        </div>
        <div class="content">
            <h2>Hello Admin,</h2>
            <p>We wanted to notify you that a customer has successfully updated their payment card information. You can now proceed with completing the payment for their ride.</p>

            <table>
                <tr>
                    <th>Customer Name</th>
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                </tr>
                <tr>
                    <th>Customer Email</th>
                    <td><a href="mailto:{{ $user->email }}">
                    {{ $user->email }}
                    </a></td>
                </tr>
            </table>

            <p>If you have any questions or need assistance, feel free to contact the support team.</p>

            <p>Best regards, <br>
            The TroyRides Team</p>
        </div>
    </div>
</body>
</html>
