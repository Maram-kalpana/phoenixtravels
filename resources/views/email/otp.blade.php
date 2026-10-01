<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP for Itinerary Download</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header img {
            max-width: 150px;
            margin-bottom: 15px;
        }
        .content {
            padding: 30px;
        }
        .otp-box {
            background: #f8f9fa;
            border: 2px dashed #667eea;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 10px;
            color: #667eea;
            margin: 10px 0;
        }
        .info-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Phoenix Tours & Travels</h1>
            <p>Your Gateway to Amazing Journeys</p>
        </div>
        
        <div class="content">
            <h2>Hello {{ $name }}!</h2>
            
            <p>Thank you for your interest in downloading our tour itinerary. To complete your download, please use the OTP (One-Time Password) below:</p>
            
            <div class="otp-box">
                <p style="margin-bottom: 10px; color: #666;">Your OTP is:</p>
                <div class="otp-code">{{ $otp }}</div>
                <p style="margin-top: 10px; color: #666; font-size: 14px;">This OTP will expire in 10 minutes</p>
            </div>
            
            <div class="info-box">
                <strong>⚠️ Important:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>This OTP is valid for single use only</li>
                    <li>Do not share this OTP with anyone</li>
                    <li>If you didn't request this, please ignore this email</li>
                </ul>
            </div>
            
            <p>If you're having trouble with the OTP, you can request a new one from the download page.</p>
            
            <p>Happy Traveling!<br>
            <strong>Phoenix Tours & Travels Team</strong></p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} Phoenix Tours & Travels. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>