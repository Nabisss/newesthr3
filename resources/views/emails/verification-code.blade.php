<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Verification Code</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f7fafc; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; }
        .code { font-size: 32px; font-weight: bold; text-align: center; color: #1a202c; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔒 CaliCrane Verification Code</h2>
        <p>Hello <strong>{{ $name }}</strong>,</p>
        <p>Your verification code is:</p>
        <div class="code">{{ $code }}</div>
        <p>This code expires in <strong>{{ $expires_in }}</strong>.</p>
        <p>If you didn't request this code, please ignore this email.</p>
    </div>
</body>
</html>
