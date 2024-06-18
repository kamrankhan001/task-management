<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>The OOP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #dddddd;
        }
        .header h1 {
            color: #333333;
        }
        .content {
            padding: 20px 0;
            color: #333333;
            line-height: 1.6;
        }
        .content a {
            color: #007bff;
            text-decoration: none;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #dddddd;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to The OOP</h1>
        </div>
        <div class="content">
            <p>Dear {{ $name }},</p>
            <p>Welcome to The OOP! We are excited to have you join our team. As part of your onboarding process, we have created an account for you in our task management system.</p>
            <p><strong>Login URL:</strong> <a href="{{ $loginUrl }}">{{ $loginUrl }}</a></p>
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Password:</strong> Your full name</p>
            <p>Please follow these steps to set up your account:</p>
            <ol>
                <li>Visit the login URL above.</li>
                <li>Enter your email.</li>
                <li>Enter your password.</li>
                <li>Follow the instructions sent to your email to complete the setup.</li>
            </ol>
            <p>If you encounter any issues during this process, our IT support team is here to help. You can reach them at itsupport@oop.com.</p>
            <p>We look forward to working with you and achieving great success together!</p>
        </div>
        <div class="footer">
            <p>Best regards,</p>
            <p>Kamran Khan<br>CEO<br>The OOP</p>
        </div>
    </div>
</body>
</html>
