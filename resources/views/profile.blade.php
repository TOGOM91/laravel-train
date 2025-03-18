<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Profil de {{ $user->username }}</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 2em;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        h1,
        h2 {
            margin-top: 0;
        }

        img {
            border-radius: 50%;
            height: 200px;
            width: 200px;
            object-fit: cover;
            margin-bottom: 1em;
        }

        .flash-message {
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin-bottom: 1em;
        }

        form {
            margin-top: 2em;
        }

        input[type="file"] {
            margin-bottom: 1em;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
            margin-top: 0.5em;
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

        <h1>Profil de {{ $user->username }}</h1>

        <form action="{{ url('/api/users/' . $user->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" value="{{ $user->username }}" required>
            <button type="submit">Mettre à jour</button>
     
          
        </form>

        <!-- Affichage de la photo de profil, ou un message s'il n'y en a pas -->
        @if($user->profile_picture)
            <img src="{{ asset('images/' . $user->profile_picture) }}" alt="Photo de profil">
        @else
            <p>Aucune photo de profil</p>
        @endif

        <h2>Mettre à jour la photo de profil</h2>
        <form action="{{ url('/api/users/' . $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <input type="file" name="profile_picture" required>
            @error('profile_picture')
                <div class="error-message">{{ $message }}</div>
            @enderror
            <br>
            <button type="submit">Mettre à jour</button>           
        </form>

        

        <form action="{{ route('api.logout') }}" method="POST">

            @csrf
            <button type="submit">Se déconnecter</button>

        </form>

        </form>
        <form action="{{ url('/api/users/' . $user->id) }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit">SUPPRIMER MON COMPTE</button>

        </form>
    </div>
</body>

</html>