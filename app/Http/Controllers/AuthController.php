<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Los datos suministrados son incorrectos'], 401);
        }

        $user = $request->user();
        if ($user->confirmation_token === null) {
            $tokenResult = $user->createToken('Personal Access Token');

            $token = $tokenResult->token;
            $token->save();

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'data' => $user,
            ]);}
            else{
                return response()->json([
                'message' => "Verifica la cuenta para acceder",
                
            ]);
            }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function me()
    {
        return response()->json(Auth::user());
    }

    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'last_name' => 'required',
    //         'email' => 'required|unique:users',
    //         'password' => 'required|min:6|confirmed',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 422);
    //     }

    //     $newEmailValue = $request->email . '@netafim.com';

    //     $user = new User();
    //     $user->name = $request->name;
    //     $user->last_name = $request->last_name;
    //     $user->email = $newEmailValue;
    //     $user->password = bcrypt($request->password);
    //     $user->save();

    //     event(new Registered($user));

    //     return response()->json(['message' => 'User registered successfully'], 201);
    // }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $updateEmail ="$request->email@netafim.com";
        $user = new User();
        $user->name = $request->name;
        $user->last_name = $request->last_name;
        $user->email = $updateEmail;
        $user->password = bcrypt($request->password);
        $user->confirmation_token = sha1($request->email . time());

        $user->save();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['token' => $user->confirmation_token]
        );

        // Envío del correo de verificación
        Mail::send('emails.verify', ['verificationUrl' => $verificationUrl], function ($message) use ($user) {
            $message->to($user->email);
            $message->subject('Verificación de cuenta');
        });

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function verify(Request $request, $token)
    {
        $user = User::where('confirmation_token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid verification token'], 400);
        }

        $user->confirmation_token = null;
        $user->email_verified_at = now();
        $user->save();

        return response()->json(['message' => 'Account verified successfully'], 200);
    }

    public function confirmAccount(Request $request, $token)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if (!$user->hasVerifiedEmail()) {
            if ($user->markEmailAsVerified()) {
                return response()->json(['message' => 'Account confirmed successfully']);
            }
        }

        return response()->json(['message' => 'Account has already been verified']);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent to your email']);
        }

        return response()->json(['error' => 'Unable to send password reset link'], 422);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $resetStatus = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => null,
                ])->save();

                $user->tokens()->delete();
            }
        );

        if ($resetStatus === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully']);
        }

        return response()->json(['error' => 'Unable to reset password'], 422);
    }
}
