<!DOCTYPE html>
<html>
<head>
    <title>Application Submitted Successfully</title>
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
        .info-box {
            background: white;
            border-left: 4px solid #800000;
            padding: 15px;
            margin: 20px 0;
        }
        .button {
            display: inline-block;
            background: #800000;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .reference {
            font-size: 18px;
            font-weight: bold;
            color: #800000;
            text-align: center;
            padding: 10px;
            background: #f0f0f0;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Application Submitted Successfully</h1>
    </div>
    
    <div class="content">
        <h2>Dear {{ $application->student->name }},</h2>
        
        <p>Your on-campus accommodation application has been successfully submitted.</p>
        
        <div class="reference">
            Application Reference: <strong>{{ $application->application_reference }}</strong>
        </div>
        
        <div class="info-box">
            <h3>Application Details:</h3>
            <p><strong>Accommodation:</strong> {{ $application->accommodation->name }}</p>
            <p><strong>Preferred Move-in Date:</strong> {{ \Carbon\Carbon::parse($application->preferred_move_in_date)->format('d M Y') }}</p>
            <p><strong>Lease Term:</strong> {{ $application->duration_months }} months</p>
            <p><strong>Status:</strong> <span style="color: #f59e0b;">Pending Review</span></p>
            <p><strong>Submitted:</strong> {{ $application->created_at->format('d M Y h:i A') }}</p>
        </div>
        
        <h3>What Happens Next?</h3>
        <ol>
            <li>The Welfare Office will review your application within 3-5 business days.</li>
            <li>You will receive an email notification when your application status changes.</li>
            <li>If approved, you will be required to pay the acceptance deposit.</li>
            <li>Once payment is confirmed, your room will be allocated.</li>
        </ol>
        
        <p style="text-align: center;">
            <a href="{{ route('student.applications') }}" class="button">Track Your Application</a>
        </p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} University of Botswana. All rights reserved.</p>
    </div>
</body>
</html>