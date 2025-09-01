<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New Contact Form Submission</title>
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
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 5px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field strong {
            display: inline-block;
            width: 120px;
            color: #495057;
        }
        .message-content {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
            margin-top: 10px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>New Contact Form Submission</h2>
        <p>A new message has been received through your website contact form.</p>
    </div>

    <div class="content">
        <div class="field">
            <strong>Booking Type:</strong> {{ $submission->booking_type }}
        </div>
        
        <div class="field">
            <strong>Name:</strong> {{ $submission->name }}
        </div>
        
        <div class="field">
            <strong>Email:</strong> <a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a>
        </div>
        
        <div class="field">
            <strong>Phone:</strong> <a href="tel:{{ $submission->phone }}">{{ $submission->phone }}</a>
        </div>
        
        <div class="field">
            <strong>Subject:</strong> {{ $submission->subject }}
        </div>
        
        <div class="field">
            <strong>Message:</strong>
            <div class="message-content">
                {{ $submission->message }}
            </div>
        </div>
        
        <div class="field">
            <strong>Submitted:</strong> {{ $submission->created_at->format('F j, Y \a\t g:i A') }}
        </div>
    </div>

    <div class="footer">
        <p>This email was automatically generated from your CMS contact form.</p>
        <p>Please respond directly to the customer's email address: {{ $submission->email }}</p>
    </div>
</body>
</html>
