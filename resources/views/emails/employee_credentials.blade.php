<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Welcome to TroyRides</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f6f9;
      margin: 0;
      padding: 0;
    }
    .container {
      background-color: #ffffff;
      max-width: 600px;
      margin: 30px auto;
      padding: 20px;
      border: 1px solid #e0e0e0;
      border-radius: 6px;
    }
    .header {
      text-align: center;
      padding-bottom: 20px;
    }
    .header h2 {
      color: #333333;
    }
    .content {
      color: #555555;
      line-height: 1.6;
    }
    .credentials {
      background-color: #f0f3f7;
      padding: 15px;
      margin: 20px 0;
      border-left: 4px solid #007bff;
    }
    .footer {
      font-size: 12px;
      color: #999999;
      text-align: center;
      padding-top: 20px;
    }
    a.button {
      display: inline-block;
      background-color: #007bff;
      color: #ffffff !important;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 4px;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>Welcome To TroyRides</h2>
        <img src="{{ url('assets/images/footer-logo.png') }}" alt="{{ config('app.name') }}" style="max-width: 150px;">  
    </div>
    <div class="content">
      <p>Dear {{$employee->first_name}} {{$employee->last_name}},</p>
      <p>We’re excited to have you on board! Your employee account has been created successfully. Please find your login credentials below:</p>
      <div class="credentials">
        <p><strong>Email:</strong> {{$employee->email}}</p>
        <p><strong>Password:</strong>  {{$tempPassword}}</p>
      </div>
      <p>To access your account, click the button below:</p>
      <p><a href="{{ url('/employee/login')}}" class="button">Login Now</a></p>
      <p>For security, please change your password after your first login. If you encounter any issues, contact our IT support at info@troyrides.com.</p>

      <p>Best regards,<br>
      TroyRides</p>
    </div>

    <div class="footer">
      © {{ date('Y') }} TroyRides. All rights reserved.
    </div>
  </div>
</body>
</html>
