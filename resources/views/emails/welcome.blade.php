<!DOCTYPE html>
<html>
<head>
    <title>Welcome to UB Accommodation System</title>
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
            background: linear-gradient(135deg, #800000 0%, #660000 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .button {
            display: inline-block;
            background: #800000;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to UB Accommodation System</h1>
    </div>
    
    <div class="content">
        <h2>Hello {{ $user->name }}!</h2>
        
        <p>Thank you for registering with the University of Botswana Accommodation System. Your account has been successfully created.</p>
        
        <h3>Next Steps:</h3>
        <ol>
            <li><strong>Verify Your Email:</strong> Click the verification link sent in a separate email to activate your account.</li>
            <li><strong>Complete Your Profile:</strong> Log in and update your profile information.</li>
            <li><strong>Upload Documents:</strong> Ensure all required documents are uploaded for verification.</li>
            <li><strong>Browse Accommodations:</strong> Start exploring on-campus and off-campus housing options.</li>
        </ol>
        
        <p>Your documents are currently <strong>pending verification</strong>. The Welfare Office will review them within 1-2 business days.</p>
        
        <a href="{{ route('login') }}" class="button">Log In to Your Account</a>
        
        <p style="margin-top: 20px;">If you have any questions, please contact the Student Welfare Office.</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} University of Botswana. All rights reserved.</p>
        <p>This is an automated message, please do not reply.</p>
    </div>
</body>
</html>