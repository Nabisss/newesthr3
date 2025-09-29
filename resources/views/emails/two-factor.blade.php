<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your CaliCrane Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7fafc;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background: #1a202c;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 30px;
        }
        .code {
            font-size: 42px;
            font-weight: bold;
            text-align: center;
            color: #2d3748;
            background: #f7fafc;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            letter-spacing: 8px;
        }
        .footer {
            background: #edf2f7;
            padding: 20px;
            text-align: center;
            color: #718096;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔒 CaliCrane Security</h1>
            <p>Verification Code Required</p>
        </div>

        <div class="content">
            <p>Hello <strong>{{ $name }}</strong>,</p>

            <p>You're attempting to login to your CaliCrane account. Use the following verification code:</p>

            <div class="code">{{ $code }}</div>

            <p><strong>This code expires in {{ $expires_in }}.</strong></p>
            <p>If you didn't request this code, please ignore this email.</p>
        </div>

        <div class="footer">
            <p>This is an automated message from CaliCrane.</p>
        </div>
    </div>
</body>
</html>
