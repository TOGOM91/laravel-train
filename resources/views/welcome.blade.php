<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Authentification</title>
    <style>
        /* Réinitialisation de base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        /* Style général du body */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        /* Conteneur principal */
        .container {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            padding: 30px;
        }
        /* Titres */
        h1 {
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
            text-align: center;
        }
        /* Style des formulaires */
        form {
            margin-bottom: 30px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 15px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #5e72e4;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #5e72e4;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #324cdd;
        }
        /* Message flash */
        .flash-message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        /* Messages d'erreur */
        .error-message {
            color: #e74c3c;
            margin-bottom: 10px;
            font-size: 14px;
        }
        /* Séparateur */
        hr {
            border: 0;
            height: 1px;
            background: #eee;
            margin: 40px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Affichage du message flash -->
        @if(session('message'))
            <div class="flash-message">
                {{ session('message') }}
            </div>
        @endif

        <h1>Inscription</h1>
        <form method="POST" action="{{ url('/api/users/register') }}">
            @csrf
            
            <div>
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" id="username">
            </div>
            @error('username', 'register')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div>
                <label for="email">Email :</label>
                <input type="email" name="email" id="email">
            </div>
            @error('email', 'register')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password">
            </div>
            @error('password','register')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <button type="submit">S'inscrire</button>
        </form>

        <hr>

        <h1>Connexion</h1>
        <form method="POST" action="{{ url('/api/users/login') }}">
            @csrf
            <div>
                <label for="login_email">Email :</label>
                <input type="email" name="email" id="login_email" >
            </div>
            @error('email', 'login')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <div>
                <label for="login_password">Mot de passe :</label>
                <input type="password" name="password" id="login_password" >
            </div>
            @error('password', 'login')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <button type="submit">Se connecter</button>
        </form>

       
    </div>
</body>
</html>
