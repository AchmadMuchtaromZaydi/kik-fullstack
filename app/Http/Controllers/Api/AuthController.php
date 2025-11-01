<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Mail\SendMailClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;

use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;

class AuthController extends Controller
{

    public function listUser()
    {
        $data = User::all();

        return response($data, 200);
    }

    public function socialLogin(Request $request)
    {

        $provider = "google"; // or $request->input('provider_name') for multiple providers
        $token_web = $request->input('access_token');
        $user = Socialite::driver($provider)->userFromToken($token_web);

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $user->getName(),
                'status' => true,
                'role' => 'user-kik'
            ]
        );
        $userCreated->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId(),
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );

        $token = auth('api')->login($userCreated);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
                'data' => null
            ], 401);
        }

        $time = auth('api')->factory()->getTTL();
        $accessToken = auth('api')->claims(['exp' => Carbon::now()->addMinutes($time)->timestamp])->login($userCreated);

        $user = auth('api')->user();
        $detailUser = new \stdClass();
        $detailUser->id = $user->id;
        $detailUser->nama = $user->name;
        $detailUser->email = $user->email;
        $detailUser->role = $user->role;
        $detailUser->foto = $user->foto;

        $detailUser->token = $accessToken;
        $detailUser->token_type = 'Bearer';
        $detailUser->expires_in = $time * 60;

        return response()->json([
            'status' => 'success',
            'message' => 'Authenticated',
            'data' => $detailUser
        ]);
    }

    public function verifyCode(Request $request)
    {
        $code = $request->code;
        $email = $request->email;

        $checkAccount = User::where("code_verified", $code)->where("email", $email)->first();

        if (!$checkAccount) {
            return response()->json([
                "status" => 'error',
                "message" => "Verification failed",
                "data" => null
            ], 400);
        }

        $checkAccount->isActive = 1;
        $checkAccount->save();

        $response = [
            "status" => 'success',
            "message" => "success. Verification success!",
        ];

        return response($response, 201);
    }

    public function register(Request $request)
    {

        // return response($request);
        // register akun

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'role' => 'required|in:admin,admin-desa,user-kik',
            'email' => 'required|string|unique:users,email',
            'whatsapp' => 'required|string|unique:users,whatsapp',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => 'error',
                "message" => $validator->errors()->all(),
                "data" => null
            ], 400);
        }

        $userCreated = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'code_verified' => random_int(100000, 999999),
            'isActive' => false
        ]);

        if ($userCreated) {
            try {
                $emailData = [
                    'subject' => 'Email Verification',
                    'recipient' => $request->email,
                    'recipient_name' => $request->name,
                    'message' => 'isi message',
                    'code' => $userCreated->code_verified
                    // Add any other data you need for the email template
                ];

                dispatch(new SendEmailJob(new SendMailClass($emailData)));

                $response = [
                    "status" => 'success',
                    "message" => "success. check email verification!",
                    "data" => $userCreated
                ];

                // or sent wa
                // $sendWhatsappController = new SendWhatsappController();
                // $data = new Request();
                // $data->merge(['to' => $request->whatsapp]);
                // $data->merge(['message' => "Code verifikasi login : " . $userCreated->whatsapp_verified]);

                // $sendWhatsappController->sendWhatsAppMessage($data);

                return response($response, 201);
            } catch (Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email gagal dikirim',
                    'data' => $e->getMessage()
                ], 400);
            }
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'not authorization!',
                'data' => null
            ], 401);
        }

        $time = auth('api')->factory()->getTTL();
        $accessToken = auth('api')->claims(['exp' => Carbon::now()->addMinutes($time)->timestamp])->attempt($credentials);

        $user = auth('api')->user();
        $detailUser = new \stdClass();
        $detailUser->id = $user->id;
        $detailUser->name = $user->name;
        $detailUser->email = $user->email;
        $detailUser->role = $user->role;
        $detailUser->foto = $user->foto;
        $detailUser->token = $accessToken;
        $detailUser->token_type = 'Bearer';
        $detailUser->expires_in = $time * 60;


        return response()->json([
            'status' => 'success',
            'message' => 'Authenticated',
            'data' => $detailUser
        ]);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refreshToken()
    {
        $token = new \stdClass();
        $token->token = auth('api')->refresh();
        $token->token_type = 'Bearer';
        $token->expires_in = auth('api')->factory()->getTTL() * 60;

        try {
            return response()->json([
                'status' => 'success',
                'message' => 'New Token',
                'data' => $token
            ]);
        } catch (TokenExpiredException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Expired refresh token',
                'data' => null
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid refresh token',
                'data' => null
            ], 401);
        } catch (TokenBlacklistedException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token has been blacklisted',
                'data' => null
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "ERROR",
                "message" => $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}
