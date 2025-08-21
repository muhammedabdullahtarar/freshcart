<?php

namespace App\Http\Controllers;

use App\Mail\VerificationEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Laravel\Passport\HasApiTokens;

class AuthController extends Controller
{
    use HasApiTokens;

    public function signin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->email_verified_at === null) {

                $this->sendVerificationEmail($user);

                return response()->json([
                    'success' => false,
                    'email_verified' => false,
                ], 403);
            }

            $token = $user->createToken('PassportToken')->accessToken;

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'type' => $user->type,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
            ], 200);
        }

        return response()->json([
            'success' => false,
        ], 401);
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('PassportToken')->accessToken;

        $this->sendVerificationEmail($user);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->token();

        $token->revoke();

        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ], 200);
    }

    private function sendVerificationEmail($user)
    {
        $encryptedId = Crypt::encrypt($user->id);
        $verificationUrl = URL::to('/verify-email/'.$encryptedId);
        Mail::to($user->email)->send(new VerificationEmail($user, $verificationUrl));
    }

    public function verifyEmail($id)
    {

        $userId = Crypt::decrypt($id);
        $user = User::find($userId);

        if (! $user) {
            return redirect('/signin');
        }

        if ($user->email_verified_at !== null) {
            return redirect('/signin');
        }

        $user->email_verified_at = Carbon::now();
        $user->save();

        return redirect('/signin');
    }

    public function resendVerificationEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at !== null) {
            return response()->json([
                'success' => false,
            ], 400);
        }

        $this->sendVerificationEmail($user);

        return response()->json([
            'success' => true,
        ]);
    }
}
