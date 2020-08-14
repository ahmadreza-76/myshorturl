<?php

namespace App\Http\Controllers;

use App\Services\ResponseService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    /**
     * register a new user with
     * -unique username
     * -unique email
     * -password
     */
    public function register(Request $request)
    {
        //check validation
        $validator = Validator::make($request->all(),
            [
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ]
        );
        if ($validator->fails()) {
            return ResponseService::response(0, 400, 'wrong inputs', $validator->errors());
        }

        //create new user
        $user = new User([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->save();

        return ResponseService::response(1, 200, null, $user);

    }

    /**
     * login with [email,pass] or [username,pass]
     * get jwt token
     */
    public function login(Request $request)
    {
        //check validation
        $validator = Validator::make($request->all(),
            [
                'usernameEmail' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string'],
            ]
        );
        if ($validator->fails()) {
            return ResponseService::response(0, 400, 'wrong inputs', $validator->errors());
        }

        //check if client using email or username
        $fieldType = filter_var($request->usernameEmail, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        //attempt to login
        if ($fieldType == 'email') {
            $token = auth()->attempt(['email' => $request->usernameEmail, 'password' => $request->password]);
        } else {
            $token = auth()->attempt(['username' => $request->usernameEmail, 'password' => $request->password]);
        }

        if (!$token) {
            return ResponseService::response(0, 406, 'login failed!');
        }

        return ResponseService::response(1, 200, 'login successfully', [
            'user' => auth()->user(),
            'token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);

    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout()
    {
        auth()->logout();

        return ResponseService::response(1, 200, 'logout successfully');
    }

    /**
    * return not authorized response
    */
    public function failed()
    {
        return ResponseService::response(0, 401, 'Invalid Access Token!');
    }

    /**
     * Refresh and generate a new token.
     */
    public function refresh()
    {
        return ResponseService::response(1,200,'',[
            'token' => auth()->refresh(),
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}
