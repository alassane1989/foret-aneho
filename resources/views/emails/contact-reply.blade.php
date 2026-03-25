<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réponse à votre message</title>
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
            background-color: #4a5568;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f7fafc;
            padding: 30px;
            border: 1px solid #e2e8f0;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .message-original {
            background-color: #edf2f7;
            padding: 15px;
            border-left: 4px solid #4a5568;
            margin: 20px 0;
            border-radius: 3px;
        }
        .reponse {
            background-color: #ebf8ff;
            padding: 20px;
            border-left: 4px solid #4299e1;
            margin: 20px 0;
            border-radius: 3px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            font-size: 0.9em;
            color: #718096;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Réponse à votre message</h2>
    </div>
    
    <div class="content">
        <p>Bonjour <strong>{{ $contact->nom }}</strong>,</p>
        
        <p>Nous faisons suite à votre message du {{ $contact->created_at->format('d/m/Y à H:i') }} concernant :</p>
        
        <div class="message-original">
            <strong>Sujet :</strong> {{ $contact->sujet_label }}<br>
            <strong>Votre message :</strong>
            <p style="margin-top: 10px;">{{ $contact->message }}</p>
        </div>
        
        <p>Voici notre réponse :</p>
        
        <div class="reponse">
            <p style="white-space: pre-line;">{{ $reponse }}</p>
        </div>
        
        <p>N'hésitez pas à nous recontacter si vous avez d'autres questions.</p>
        
        <p>Cordialement,<br>
        L'équipe de l'Observatoire National des Violences en milieu scolaire</p>
    </div>
    
    <div class="footer">
        <p>Cet email a été envoyé depuis l'Observatoire National des Violences en milieu scolaire.</p>
    </div>
</body>
</html>