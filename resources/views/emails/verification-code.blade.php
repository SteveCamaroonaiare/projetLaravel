<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de votre compte</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }
        .content {
            padding: 20px;
        }
        .verification-code {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            margin: 30px 0;
            letter-spacing: 5px;
            color: #4f46e5;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Vérification de votre compte</h1>
        </div>
        
        <div class="content">
            <p>Bonjour {{ $user->firstName }} {{ $user->lastName }},</p>
            
            <p>Merci de vous être inscrit sur notre site. Pour activer votre compte, veuillez utiliser le code de vérification ci-dessous:</p>
            
            <div class="verification-code">{{ $verificationCode }}</div>
            
            <p>Ce code est valable pendant 24 heures. Si vous n'avez pas demandé cette vérification, vous pouvez ignorer cet email.</p>
            
            <p>Cordialement,<br>L'équipe de notre site</p>
        </div>
        
        <div class="footer">
            <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
