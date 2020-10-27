<?php

namespace App\Http\Controllers\Installation;

use App\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    /**
     * Create admin account
     *
     * @param Request $request
     * @return void
     */
    public function account(Request $request)
    {
        // Validate request
        $request->validate([
            'username' => 'required|max:60|unique:users,username',
            'email'    => 'required|max:60|email|unique:users,email',
            'password' => 'required|max:60|min:6|confirmed',
        ]);

        // Created user
        $user = User::create([
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
            'isActive'  => true,
            'isPending' => false
        ]);

        // Success
        return response([]);
    }
}
