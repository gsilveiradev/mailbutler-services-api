<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Requests\Api\Authentication\AuthenticationRequest;
use App\Http\Requests\Api\Authentication\AuthenticationForgotPasswordRequest;
use App\Http\Requests\Api\Authentication\AuthenticationChangePasswordRequest;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use PGSchema;
use Mail;
use Hash;

class AuthenticationController extends Controller
{
    public function __construct()
    {
       //
    }

    public function authenticate(AuthenticationRequest $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }


        $user = User::where('email', $request->email)->first();

        $user['token'] = $token;

        $responseAuth = new Response();
        $responseAuth->header('Authorization', 'Bearer '.$token);
        $responseAuth->setContent($user);

        return $responseAuth;
    }

    public function forgotPassword(AuthenticationForgotPasswordRequest $request)
    {
        $user = User::where('email', '=', $request->email)->first();

        if (!$user) {
            return response()->json(array(
                'message' => 'Validation error',
                'errors' => array('email' => array('E-mail not found.'))
            ), 422);
        }

        $newPassword = str_random(8);

        $user->password = Hash::make($newPassword);
        $user->save();

        // Send email with new password
        Mail::send('emails.forgot_password', [
            'user' => $user,
            'new_password' => $newPassword
        ], function ($message) use ($user) {
            $message->to($user->email, $user->name)
                ->subject('Recover your account password');
        });

        return response()->json(array(
            'email' => $user->email
        ), 200);
    }

    public function changePassword(AuthenticationChangePasswordRequest $request)
    {
        JWTAuth::parseToken();

        $user = JWTAuth::parseToken()->authenticate();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(array(
                'message' => 'Validation error',
                'errors' => array('The actual password does not match.')
            ), 422);
        }

        $newPassword = $request->password;

        $user->password = Hash::make($newPassword);
        $user->save();

        return response()->json(compact('user'));
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(array(
            'message' => 'Ok!'
        ), 200);
    }

    public function refreshToken()
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            throw new BadRequestHtttpException('Token not provided');
        }
        try {
            $token = JWTAuth::refresh($token);
        } catch (TokenInvalidException $e) {
            throw new AccessDeniedHttpException('The token is invalid');
        }

        return response()->json(array(
            'token' => $token
        ), 200);
    }
}
