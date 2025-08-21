<!DOCTYPE html>
<html>
<head>
    <title>Email Verification - FreshCart</title>
    <style>
        /* Custom styles for better email client compatibility and specific design */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td, div {
            box-sizing: border-box;
        }
        /* Ensure images are responsive */
        img {
            max-width: 100%;
            height: auto;
            display: block;
        }
        /* Custom button styling for better compatibility */
        .btn-primary {
            display: inline-block;
            background-color: #4CAF50; /* Original Green */
            color: #ffffff !important;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #45a049; /* Darker Green */
        }
        /* Styles for the link input section */
        .link-input-container {
            background-color: #f9f9f9;
            border: 1px solid #ddd; /* Original border color */
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
        }
        .verification-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            color: #333;
            background-color: #ffffff;
            overflow-x: auto; /* Allow horizontal scrolling for long URLs */
            white-space: nowrap; /* Prevent wrapping */
            cursor: text; /* Indicate it's selectable */
        }
        .container {
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 12px;
            overflow: hidden;
            margin-top: 32px;
            margin-bottom: 32px;
        }
        .header {
            background-color: #4CAF50;
            color: #ffffff;
            padding: 24px;
            text-align: center;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .header h1 {
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 8px;
            line-height: 1.25;
        }
        .header p {
            font-size: 18px;
            line-height: 1.5;
        }
        .content {
            padding: 32px;
        }
        .content-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .content-header h2 {
            font-size: 24px;
            font-weight: 600;
            color: #1a202c;
        }
        .content p {
            color: #4a5568;
            margin-bottom: 16px;
        }
        .content h3 {
            margin: 0;
            font-size: 16px;
            line-height: 1.5;
        }
        .button-section {
            text-align: center;
            margin-bottom: 32px;
        }
        .link-display-section {
            padding: 16px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            margin-top: 24px;
        }
        .link-display-section p {
            color: #4a5568;
            font-size: 14px;
            margin-bottom: 12px;
        }
        .footer {
            background-color: #f7f7f7;
            padding: 24px;
            text-align: center;
            color: #718096;
            font-size: 14px;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
        }
        .footer p {
            margin: 0;
        }
        .footer .address {
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>FreshCart</h1>
            <p>Your Daily Dose of Freshness</p>
        </div>

        <div class="content">
            <div class="content-header">
                <h2>Verify Your Account</h2>
            </div>

            <p>
                <h3>Hello {{ $user->name }},</h3>
                Thank you for registering with FreshCart!
            </p>
            <p>
                Please click the button below to verify your email address:
            </p>

            <div class="button-section">
                <a href="{{ $verificationUrl }}" class="btn-primary">Verify Email Address</a>
            </div>

            <p>
                If you did not create an account, no further action is required.
            </p>

            <p style="color: #4a5568; font-weight: 600; margin-top: 24px;">
                The FreshCart Team
            </p>

            <div class="link-display-section">
                <p>
                    If the button doesn't work, copy and paste this link into your browser:
                </p>
                <input type="text" value="{{ $verificationUrl }}" class="verification-input" readonly onclick="this.select()">
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} FreshCart. All rights reserved.</p>
            <p class="address">123 Fresh Food Lane, Harvest City, FC 12345</p>
        </div>
    </div>
</body>
</html>