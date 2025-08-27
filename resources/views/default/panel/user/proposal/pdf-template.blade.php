<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Business Proposal - {{ $project_title }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .content {
            font-size: 12px;
        }
        .content h2 {
            color: #007bff;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-top: 25px;
            margin-bottom: 15px;
        }
        .content h3 {
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUSINESS PROPOSAL</h1>
        <p><strong>Project:</strong> {{ $project_title }}</p>
        <p><strong>Client:</strong> {{ $client_name }}</p>
        <p><strong>Date:</strong> {{ $generated_date }}</p>
    </div>

    <div class="content">
        {!! nl2br(e($proposal_content)) !!}
    </div>

    <div class="footer">
        <p>Generated on {{ $generated_date }} | Professional Business Proposal</p>
    </div>
</body>
</html>
