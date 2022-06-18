<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use App\Notifications\WelcomeNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Session;
use Hash;
use App\Common\Utility;

class AuthController extends Controller
{

    /**
     * Register a new User and notify them on mail with password
     *
     * @param  \Illuminate\Http\Request  $request
    */
    
    public function register(Request $request)
    {
        Utility::stripXSS();
    	//Validate data
        $data = $request->only('name', 'email', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
        ]);
        
        //Send failed response if request is not valid
        if ($validator->fails()) {
            
            if ($request->wantsJson()) {
                return response()->json(['error' => $validator->messages()], 200);
            } else {
                return redirect()->back()->withInput()
                ->withErrors($validator->messages());
            }
            
        }
        $random_password = Str::random(10);
        //Request is valid, create new user
        $user = User::create([
        	'name' => $request->name,
        	'email' => $request->email,
            'password' => Hash::make($random_password)
        ]);
        
        // nitify user
        $user->notify(new WelcomeNotification($random_password));

        //User created, return success response
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => $user
            ], Response::HTTP_OK);
        } else {
            return redirect()->intended('/')
            ->withSuccess('Category Added Successfully');
        }
    }

    /**
     * authenticate user and returns token
     *
     * @param  \Illuminate\Http\Request  $request
     */
 
    public function authenticate(Request $request)
    {
        Utility::stripXSS();
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
 	
 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    /**
     * Web baes authentication system
     *
     * @param  \Illuminate\Http\Request  $request
     */

    public function webLogin(Request $request)
    {
        Utility::stripXSS();
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('/')->withInput()
                        ->withSuccess('You have Successfully loggedin');
        }
  
        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }

    
    /**
     * Api Bases Logout to delete the token 
     *
     * @param  \Illuminate\Http\Request  $request
     */

    public function logout(Request $request)
    {
        Utility::stripXSS();
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

		//Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * WEb Based Logout 
     *
     */
    public function weblogout() {
        Session::flush();
        Auth::logout();
  
        return Redirect('login');
    }
}