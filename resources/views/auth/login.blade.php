<form action="{{ route('api.login') }}" method="POST">
@csrf
<div>
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Mot de passe">
    <button type="submit">Se connecter</button>

</div>


</form>