<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reset Password</title>
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f5f5f5;
        margin: 0; 
        padding: 0;
    }
    .email-container {
        max-width: 600px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        padding: 30px;
        text-align: left; /* LEFT align */
    }
    .logo {
        display: block;
        width: 120px;
        margin-bottom: 20px;
        margin-left: auto;
        margin-right: auto;
    }
    h1 {
        color: #1f2937;
        font-size: 24px;
        margin-bottom: 20px;
    }
    p {
        color: #4b5563;
        font-size: 16px;
        line-height: 1.5;
    }
    /* Center the Reset Password Button */
    .btn-container {
        display: flex;
        justify-content: center; /* Centers the button horizontally */
        margin: 25px 0;
    }
    .btn {
        padding: 14px 28px;
        background-color: #1f2937;
        color: #ffffff;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
    }
    .btn:hover {
        background-color: #6fb9e4;
    }
    .footer {
        font-size: 14px;
        color: #9ca3af;
        margin-top: 20px;
        text-align: center;
    }
    .fallback-url {
        font-size: 14px;
        color: #1f2937;
        word-break: break-all;
    }
</style>
</head>
<body>
<div class="email-container">
    <!-- Embedded Toyoflex Logo -->

    <h1>Hello!</h1>

    <p>You are receiving this email because we received a password reset request for your account.</p>

    <!-- Reset Password Button Centered -->
    <div class="btn-container">
        <a href="{{ $url }}" class="btn">Reset Password</a>
    </div>

    <p>This password reset link will expire in 60 minutes.</p>
    <p>If you did not request a password reset, no further action is required.</p>

    <!-- Fallback URL -->
    <p class="fallback-url">
        If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:<br>
        <a href="{{ $url }}">{{ $url }}</a>
    </p>

    <div class="footer">
        &copy; {{ date('Y') }} Toyoflex. All rights reserved.
    </div>
</div>
</body>
</html>
