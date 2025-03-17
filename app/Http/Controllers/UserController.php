<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class UserController extends Controller
{

    //get all users 
    public function index()
    {
        return response()->json(User::all());

    }
 
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:4',

        ]);
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'bio' => $request->bio,
            'profile_picture' => $request->profile_picture
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
        'message' => 'User registerd successfully',
        'user' => $user,
        'token'=> $token
        ], 201);
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
        'username' => 'string|max:255|unique:users,username,'.$user->id,
        'email' => 'email|unique:users,email,'.$user->id,
        'password' => 'nullable|string|min:4|confirmed',
       
    ]);

    if (!empty($data['password'])) {
        $data['password'] = bcrypt($data['password']);
    } else {
        unset($data['password']);
    }

    $user->update($data);

    return response()->json($user, 200);
}

   

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['message' => 'User deleted', 200]);

    }
}