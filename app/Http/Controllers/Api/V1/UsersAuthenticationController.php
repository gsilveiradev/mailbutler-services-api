<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Mail\ForgotPassword;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\Api\V1\UserAuthenticationRequest;
use App\Http\Requests\Api\V1\UserAuthenticationForgotPasswordRequest;
use App\Http\Requests\Api\V1\UserAuthenticationChangePasswordRequest;
use App\Repositories\Api\V1\UserRepository;
use App\Validators\Api\V1\UserValidator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail;
use Hash;

class UsersAuthenticationController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var UserValidator
     */
    protected $validator;

    public function __construct(UserRepository $repository, UserValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Authenticate an User.
     *
     * @param  AuthenticationRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function authenticate(UserAuthenticationRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (! $token = JWTAuth::attempt($credentials)) {
                $response = [
                    'error'   => true,
                    'message' => 'Invalid credentials.'
                ];

                return response()->json($response, 401);
            }

            $user = $this->repository->skipPresenter()->findByField('email', $request->email)->first();
            $user = $user->presenter();
            $user['token'] = $token;

            $response = [
                'message' => 'User logged.',
                'data'    => $user,
            ];

            return response()->json($response);
        } catch (JWTException $e) {
            return response()->json([
                'error'   => true,
                'message' => 'Could not create token.'
            ], 500);
        }
    }

    public function forgotPassword(UserAuthenticationForgotPasswordRequest $request)
    {
        try {
            $user = $this->repository->skipPresenter()->findByField('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'error' => true,
                    'message' => ['email' => ['E-mail not found.']]
                ], 422);
            }

            $newPassword = str_random(8);
            $userArray = $user->toArray();
            $userArray['password'] = Hash::make($newPassword);

            $this->validator->setId($user->id);
            $user = $this->repository->update($userArray, $user->id);

            // Send email with new password
            $message = (new ForgotPassword($user, $newPassword))
                ->onQueue('emails');

            Mail::to($user->email, $user->name)->queue($message);

            $response = [
                'message' => 'User forgot password instructions sent by email.',
                'data'    => $user->presenter(),
            ];

            return response()->json($response);
        } catch (ValidatorException $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessageBag()
            ]);
        }
    }

    public function changePassword(UserAuthenticationChangePasswordRequest $request)
    {
        try {
            JWTAuth::parseToken();

            $user = JWTAuth::parseToken()->authenticate();

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'error'   => true,
                    'message' => ['password' => ['The actual password does not match.']]
                ], 422);
            }

            $newPassword = $request->password;

            $userArray = $user->toArray();
            $userArray['password'] = Hash::make($newPassword);

            $this->validator->setId($user->id);
            $user = $this->repository->update($userArray, $user->id);

            $response = [
                'message' => 'User password changed.',
                'data'    => $user,
            ];

            return response()->json($response);
        } catch (ValidatorException $e) {
            return response()->json([
                'error'   => true,
                'message' => $e->getMessageBag()
            ]);
        }
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        $response = [
            'message' => 'User logged out.'
        ];

        return response()->json($response);
    }

    public function refreshToken()
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            return response()->json([
                'error' => true,
                'message' => 'Token not provided.'
            ], 400);
        }
        
        try {
            $token = JWTAuth::refresh($token);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'error' => true,
                'message' => 'Token is invalid.'
            ], 403);
        }

        $response = [
            'message' => 'Token refreshed.',
            'data'    => ['token' => $token],
        ];

        return response()->json($response);
    }
}
