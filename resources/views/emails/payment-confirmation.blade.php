<!DOCTYPE html>
<html>
<head>
    <title>Payment Confirmation</title>
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
        .receipt {
            background: white;
            padding: 20px;
            border: 2px dashed #800000;
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment Confirmation</h1>
    </div>
    
    <div class="content">
        <h2>Dear {{ $payment->student->name }},</h2>
        
        <p>Your payment has been successfully processed. Thank you for your prompt payment.</p>
        
        <div class="receipt">
            <h3 style="text-align: center; margin-top: 0;">PAYMENT RECEIPT</h3>
            <table>
                <tr>
                    <td><strong>Transaction ID:</strong></td>
                    <td>{{ $payment->transaction_id }}</td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td>{{ $payment->paid_at->format('d M Y h:i A') }}</td>
                </tr>
                <tr>
                    <td><strong>Payment Type:</strong></td>
                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</td>
                </tr>
                <tr>
                    <td><strong>Amount:</strong></td>
                    <td><strong>P{{ number_format($payment->amount, 2) }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Payment Method:</strong></td>
                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                </tr>
                <tr>
                    <td><strong>Status:</strong></td>
                    <td><span style="color: #10b981;">COMPLETED</span></td>
                </tr>
            </table>
        </div>
        
        @if($payment->application)
            <p>This payment is for <strong>Application #{{ $payment->application->application_reference }}</strong></p>
        @endif
        
        @if($payment->property)
            <p>This payment is for <strong>{{ $payment->property->title }}</strong></p>
        @endif
        
        <p style="text-align: center;">
            <a href="{{ route('student.payments') }}" class="button">View Payment History</a>
        </p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} University of Botswana. All rights reserved.</p>
    </div>
</body>
</html>