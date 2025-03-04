<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            padding: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 0.9em;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to {{ config('app.name') }}!</h1>
    </div>

    <div class="content">
        <p>Dear {{ $user->name }},</p>

        <p>Welcome to {{ config('app.name') }}! We're excited to have you on board.</p>

        <p>Your account has been successfully created with the following details:</p>
        <ul>
            <li>Name: {{ $user->name }}</li>
            <li>Email: {{ $user->email }}</li>
            <li>Role: {{ $user->getRoleNames()->first() }}</li>
        </ul>

        <p>You can now login to your account using your email address and the password you provided during registration.</p>

        <center>
            <a href="{{ route('login') }}" class="button">Login to Your Account</a>
        </center>

        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>

        <p>Best regards,<br>The {{ config('app.name') }} Team</p>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p>This is an automated email, please do not reply.</p>
    </div>
</body>
</html>
