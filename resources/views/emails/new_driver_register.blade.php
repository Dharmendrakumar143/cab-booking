<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Driver Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            font-size: 22px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }
        .content {
            padding: 20px;
            color: #333;
            font-size: 16px;
            line-height: 1.6;
        }
        .button {
            display: block;
            width: 200px;
            text-align: center;
            background: #007bff;
            color: #ffffff;
            padding: 12px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px auto;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 14px;
            color: #777;
            background: #f4f4f4;
            border-radius: 0 0 10px 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">New Driver Registration</div>
    <div class="content">
        <p>Dear Admin,</p>
        <p>A new driver has successfully registered on the platform. Below are the details:</p>
        
        <strong>Driver Name:</strong> {{$driver->first_name}} {{$driver->last_name}}<br>
        <strong>Email:</strong> {{$driver->email}}<br>

        <p>Please review and verify the driver's documents to approve their account.</p>

        <a href="{{ url('/admin/drivers') }}" class="button">View Driver Details</a>
        
        <!-- <p>If you have any questions, please contact our support team.</p> -->
        
        <p>Best regards,<br>
        Troy Rides</p>
    </div>
    <div class="footer">
        © {{ date('Y') }} Troy Rides. All rights reserved.
    </div>
</div>

</body>
</html>
