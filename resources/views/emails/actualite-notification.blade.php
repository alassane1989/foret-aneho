{{-- resources/views/emails/actualite-notification.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle actualité : {{ $actualite->titre }}</title>
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
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .actualite-image {
            text-align: center;
            margin-bottom: 20px;
        }
        .actualite-image img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .actualite-categorie {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            margin-bottom: 15px;
        }
        .actualite-titre {
            font-size: 24px;
            margin: 15px 0;
            color: #28a745;
        }
        .actualite-description {
            font-size: 16px;
            color: #666;
            margin: 20px 0;
            line-height: 1.6;
        }
        .actualite-contenu {
            margin: 20px 0;
            line-height: 1.8;
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #218838;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
        }
        .unsubscribe-link {
            color: #28a745;
            text-decoration: none;
        }
        .unsubscribe-link:hover {
            text-decoration: underline;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            color: #28a745;
            margin: 0 10px;
            text-decoration: none;
        }
        .tags {
            margin: 20px 0;
            font-size: 12px;
            color: #999;
        }
        .tag {
            display: inline-block;
            background: #f0f0f0;
            padding: 3px 8px;
            border-radius: 3px;
            margin: 0 2px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
            }
            .content {
                padding: 20px;
            }
            .actualite-titre {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📰 Nouvelle actualité</h1>
            <p>Découvrez notre dernière publication</p>
        </div>
        
        <div class="content">
            @if($actualite->image_principale)
            <div class="actualite-image">
                <img src="{{ $actualite->image_url }}" alt="{{ $actualite->titre }}">
            </div>
            @endif
            
            <div style="text-align: center;">
                <span class="actualite-categorie">
                    {{ $actualite->categorie_formatee }}
                </span>
            </div>
            
            <h2 class="actualite-titre">{{ $actualite->titre }}</h2>
            
            <div class="actualite-description">
                {{ $actualite->description_courte }}
            </div>
            
            <div class="actualite-contenu">
                {!! \Illuminate\Support\Str::limit(strip_tags($actualite->contenu), 500) !!}
            </div>
            
            @if($actualite->tags)
            <div class="tags">
                Tags : 
                @php
                    $tags = is_array($actualite->tags) ? $actualite->tags : json_decode($actualite->tags, true);
                @endphp
                @if(is_array($tags))
                    @foreach($tags as $tag)
                        <span class="tag">#{{ $tag }}</span>
                    @endforeach
                @endif
            </div>
            @endif
            
            <div style="text-align: center;">
                <a href="{{ route('actualites.show', $actualite->slug) }}" class="btn">
                    Lire l'article complet →
                </a>
            </div>
        </div>
        
        <div class="footer">
            <p>Bonjour <strong>{{ $subscriber->nom }}</strong>,</p>
            <p>Vous recevez cet email car vous êtes inscrit à notre newsletter.</p>
            <p>
                <a href="{{ route('newsletter.unsubscribe', ['email' => $subscriber->email]) }}" class="unsubscribe-link">
                    Se désabonner
                </a>
            </p>
            <div class="social-links">
                <a href="#">Facebook</a> |
                <a href="#">Twitter</a> |
                <a href="#">LinkedIn</a>
            </div>
            <p style="margin-top: 20px;">
                &copy; {{ date('Y') }} Votre Site. Tous droits réservés.
            </p>
        </div>
    </div>
</body>
</html>