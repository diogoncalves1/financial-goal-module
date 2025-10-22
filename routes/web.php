<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Entities\User;


Route::get('xxx', function (Request $request) {

    User::create([
        'email' => 'test@gmail.com',
        'password' => Hash::make('12345678'),
        'name' => 'test'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages(['email' => ['Credenciais invalidas.']]);
    }

    $token = $user->createToken('web-token')->plainTextToken;

    return response()->json(['user' => $user, 'token' => $token], 201);
});
