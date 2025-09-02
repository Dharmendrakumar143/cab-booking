<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Verification Status</title>
    <style>
        /* Global Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            color: #333;
        }

        table {
            border-spacing: 0;
            width: 100%;
        }

        td {
            padding: 0;
        }

        .email-container {
            background-color: #ffffff;
            margin: 0 auto;
            padding: 30px;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            color: #333333;
            text-align: center;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            color: #555555;
            line-height: 1.6;
            margin: 0 0 20px;
            text-align: center;
        }

        .button {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 12px 20px;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #45a049;
        }

        .footer {
            font-size: 14px;
            color: #777777;
            text-align: center;
            margin-top: 40px;
        }

        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>
    <table role="presentation" cellspacing="0" cellpadding="0" width="100%">
        <tr>
            <td align="center">
                <div class="email-container">
                    <!-- Header Section -->
                    <h1>Document Verification Status</h1>

                    <!-- Body Section -->
                    <p>Dear {{ $verify->name }},</p>

                    <p>We have reviewed the documents you submitted for verification. your profile has been <strong>{{ $verify->document_verification_status }}</strong>.</p>

                    @if ($verify->document_verification_status == 'rejected')
                        <p>To proceed, please <strong>reupload your documents</strong> with the necessary corrections or updates. Ensure all documents are clear and meet our requirements.</p>
                    @elseif ($verify->document_verification_status == 'approved')
                        <p>Your profile has been approved and is now active.</p>
                    @endif

                    <!-- Footer Section -->
                    <div class="footer">
                        <p>If you have any questions or need further assistance, please contact our support team.</p>
                        <p>© {{ date('Y') }} Troy Rides. All rights reserved.</p>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
