<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Psr7\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{

    public function showLogin()
    {
        return view("auth.login");
    }

    public function showRegister()
    {
        return view("auth.register");
    }
    public function me(Request $request)
    {

        return response()->json($request->user(), 200);
    }

    //get all users 
    public function index()
    {
        return response()->json(User::all());

    }

    public function register(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:4',
        ], [
            'username.required' => 'Nom d\'utilisateur est obligatoire',
            'username.unique' => 'Nom d\'utilisateur existe déjà',
            'username.max' => 'Nom d\'utilisateur doit contenir au maximum 255 caractères',
            'email.required' => 'Email est obligatoire',
            'email.email' => 'Email invalide',
            'email.unique' => 'Email existe déjà',
            'password.required' => 'Mot de passe est obligatoire',
            'password.min' => 'Mot de passe doit contenir au moins 4 caractères',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'register')
                ->withInput();
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'bio' => $request->bio,
            'profile_picture' => $request->profile_picture,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        auth()->login($user);
        session()->put('token', $token);

        return redirect('/api/profile')->with('message', 'Utilisateur enregistré avec succès !');
    }


    public function login(Request $request)
    {
        // Créer une instance de validateur pour le formulaire de connexion
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:4',
        ], [
            'email.required' => 'Email est obligatoire',
            'email.email' => 'Email invalide',
            'password.required' => 'Mot de passe est obligatoire',
            'password.min' => 'Mot de passe doit contenir au moins 4 caractères',
        ]);

        // En cas d'erreurs de validation, rediriger avec l'error bag "login"
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'login')
                ->withInput();
        }

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->withErrors(['email' => 'Identifiants invalides'], 'login')
                ->withInput();
        }

        // Créer un token et connecter l'utilisateur
        $token = $user->createToken('auth_token')->plainTextToken;
        session()->put('token', $token);
        auth()->login($user);

        // Rediriger vers la page de profil avec un message flash
        return redirect('/api/profile')->with('message', 'Utilisateur connecté avec succès !');
    }


    public function logout(Request $request)
    {
        $currentToken = $request->user()->currentAccessToken();
        
        if ($currentToken && method_exists($currentToken, 'delete')) {
            $currentToken->delete();
        }
        
        return redirect('/')->with('message', 'Déconnexion réussie');
    }
    

    //get one user 
    public function show(User $user)
    {
        return response()->json($user, 201);

    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $data = $request->validate([
            'username' => 'string|max:255|unique:users,username,' . $user->id,
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:4|confirmed',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'username.unique' => 'Nom d\'utilisateur existe déjà',
            'username.max' => 'Nom d\'utilisateur doit contenir au maximum 255 caractères',
            'email.unique' => 'Email existe déjà',
            'email.email' => 'Email invalide',
            'password.min' => 'Le mot de passe doit contenir au moins 4 caractères',
            'profile_picture.image' => 'Le fichier doit être une image',
            'profile_picture.mimes' => 'Le fichier doit être de type : jpeg, png, jpg, gif, svg',
            'profile_picture.max' => 'Le fichier ne doit pas dépasser 2 Mo',
        ]);
    
        // Traitement du fichier photo s'il est présent et validé
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
            $data['profile_picture'] = $imageName;
        }
    
        // Traitement du mot de passe
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
    
        $user->update($data);
    
        // Pour un contexte web, on redirige avec un message flash
        return redirect()->back()->with('message', 'Profil mis à jour avec succès');
    }
    



    public function destroy(Request $request, $id)
    {
        // Récupère l'utilisateur connecté
        $user = $request->user();
        
        // Vérifie que l'utilisateur connecté existe et que son ID correspond à l'ID fourni dans l'URL
        if (!$user || $user->id != $id) {
            return response()->json(['message' => 'User not found'], 404);
        }
        
        // Supprime les tokens liés à l'utilisateur
        $user->tokens()->delete();
        
        // Supprime l'utilisateur
        $user->delete();
        
        return redirect('/')->with('message', 'Compte supprimé avec succès !');
    }
    
    public function profile(Request $request)
{
    // Vérifie si l'utilisateur est authentifié
    if (!auth()->check()) {
        // Redirige vers la page "welcome" si aucun utilisateur n'est connecté
        return redirect()->route('welcome');
    }

    // Récupère l'utilisateur connecté
    $user = auth()->user();

    // Retourne la vue "profile" en passant les données de l'utilisateur
    return view('profile', compact('user'));
}

}