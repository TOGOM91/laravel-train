<form action="{{ url('/api/users/register') }}" method="POST">
    @csrf
    <div>
        <input type="text" name="username" placeholder="Nom d'utilisateur">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Mot de passe">
        <button type="submit">Register</button>
    
    </div>
    
    
    </form>