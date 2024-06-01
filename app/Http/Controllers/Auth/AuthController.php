<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\OTPLogin;
use App\Models\OTPRecovery;
use App\Models\OTPRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function checkExistingNumber(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:11|max:13'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            return response([
                'status' => true,
                'message' => 'Phone number already exists',
                'data' => $user->phone
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Phone number does not exist',
                'data' => null
            ], 200);
        }
    }

    public function sendRegisterOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:11|max:13'
        ]);

        $otp = OTPRegistration::create([
            'phone' => $request->phone,
            'otp' => rand(100000, 999999),
            'created_at' => now(),
            'expires_at' => now()->addMinutes(5)
        ]);

        $data = [
            'target' => $request->phone,
            'message' => 'Jangan berikan kode OTP ini ke siapa pun. Kode OTP ini hanya berlaku 5 menit. Kode OTP anda adalah ' . $otp->otp,
        ];

        $response = Http::withHeaders(['Authorization' => 'sCa+fZkAQKgPFSrMm4iQ', 'Content-Type' => 'application/json'])->post('https://api.fonnte.com/send', $data);

        return $response->getBody();
    }

    public function sendLoginOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:11|max:13'
        ]);

        $otp = OTPLogin::create([
            'phone' => $request->phone,
            'otp' => rand(100000, 999999),
            'created_at' => now(),
            'expires_at' => now()->addMinutes(5)
        ]);

        $data = [
            'target' => $request->phone,
            'message' => 'Jangan berikan kode OTP ini ke siapa pun. Kode OTP ini hanya berlaku 5 menit. Kode OTP anda untuk login adalah ' . $otp->otp,
        ];

        $response = Http::withHeaders(['Authorization' => 'sCa+fZkAQKgPFSrMm4iQ', 'Content-Type' => 'application/json'])->post('https://api.fonnte.com/send', $data);

        return $response->getBody();
    }

    public function sendRecoveryOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:11|max:13'
        ]);

        $otp = OTPRegistration::create([
            'phone' => $request->phone,
            'otp' => rand(100000, 999999),
            'created_at' => now(),
            'expires_at' => now()->addMinutes(5)
        ]);

        $data = [
            'target' => $request->phone,
            'message' => 'Jangan berikan kode OTP ini ke siapa pun. Kode OTP ini hanya berlaku 5 menit. Kode OTP anda untuk login adalah ' . $otp->otp,
        ];

        $response = Http::withHeaders(['Authorization' => 'sCa+fZkAQKgPFSrMm4iQ', 'Content-Type' => 'application/json'])->post('https://api.fonnte.com/send', $data);

        return $response->getBody();
    }

    public function verifyRecoveryOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:11|max:13',
            'otp' => 'required|string|min:6|max:6'
        ]);

        $allOTP = OTPRecovery::where('phone', $request->phone);

        $otp = OTPRecovery::where('phone', $request->phone)
            ->where('otp', $request->otp)
            // ->where('expires_at', '>=', now())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($otp) {
            $isExpired = $otp->expires_at <= now();

            if ($isExpired) {
                $allOTP->delete();
                return response([
                    'status' => false,
                    'message' => 'OTP expired',
                    'data' => [
                        'reason' => 'OTP expired'
                    ]
                ], 200);
            } else {
                $allOTP->delete();
                $otp->delete();
                return response([
                    'status' => true,
                    'message' => 'OTP verified successfully',
                    'data' => $otp->otp
                ], 200);
            }
        } else {
            return response([
                'status' => false,
                'message' => 'OTP verification failed',
                'data' => [
                    'reason' => 'OTP is invalid'
                ]
            ], 200);
        }
    }

    public function verifyRegistrationOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:11|max:13',
            'otp' => 'required|string|min:6|max:6'
        ]);

        $allOTP = OTPRegistration::where('phone', $request->phone);

        $otp = OTPRegistration::where('phone', $request->phone)
            ->where('otp', $request->otp)
            // ->where('expires_at', '>=', now())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($otp) {
            $isExpired = $otp->expires_at <= now();

            if ($isExpired) {
                $allOTP->delete();
                return response([
                    'status' => false,
                    'message' => 'OTP expired',
                    'data' => [
                        'reason' => 'OTP expired'
                    ]
                ], 200);
            } else {
                $allOTP->delete();
                $otp->delete();
                return response([
                    'status' => true,
                    'message' => 'OTP verified successfully',
                    'data' => $otp->otp
                ], 200);
            }
        } else {
            return response([
                'status' => false,
                'message' => 'OTP verification failed',
                'data' => [
                    'reason' => 'OTP is invalid'
                ]
            ], 200);
        }
    }

    public function verifyLoginOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:11|max:13',
            'otp' => 'required|string|min:6|max:6'
        ]);

        $allOTP = OTPLogin::where('phone', $request->phone);

        $otp = OTPLogin::where('phone', $request->phone)
            ->where('otp', $request->otp)
            // ->where('expires_at', '>=', now())
            ->orderBy('created_at', 'desc')
            ->first();

        if ($otp) {
            $isExpired = $otp->expires_at <= now();

            if ($isExpired) {
                $allOTP->delete();
                return response([
                    'status' => false,
                    'message' => 'OTP expired',
                    'data' => [
                        'reason' => 'OTP expired'
                    ]
                ], 200);
            } else {
                $otp->delete();
                $allOTP->delete();
                return response([
                    'status' => true,
                    'message' => 'OTP verified successfully',
                    'data' => $otp->otp
                ], 200);
            }
        } else {
            return response([
                'status' => false,
                'message' => 'OTP verification failed',
                'data' => [
                    'reason' => 'OTP is invalid'
                ]
            ], 200);
        }
    }

    public function register(RegisterRequest $request)
    {
        $request->validated();

        $userData = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'bday' => date('Y-m-d', strtotime($request->bday)),
            'pin' => Hash::make($request->pin),
        ];

        $user = User::create($userData);

        return response([
            'status' => true,
            'message' => 'Registration successful',
            'data' => $user
        ]);
    }

    public function login(LoginRequest $request)
    {
        $request->validated();

        $user = User::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->pin, $user->pin)) {
            return response([
                'status' => false,
                'message' => 'Invalid credentials',
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response([
            'status' => true,
            'message' => 'Login successful',
            'data' => $user,
            'access_token' => $token,
        ], 200);
    }

    public function resetPIN(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:11|max:13',
            'pin' => 'required|string|min:6|max:6'
        ]);

        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            $user->pin = Hash::make($request->pin);
            $user->save();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response([
                'status' => true,
                'message' => 'PIN reset successful',
                'data' => $user,
                'access_token' => $token,
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Phone reset failed',
                'data' => null
            ], 200);
        }
    }

    public function logout(Request $request)
    {
        // $request = $user->tokens()->where('id', $tokenId)->delete();

        return response([
            'status' => true,
            'message' => 'Logout successful',
            'data' => null
        ], 200);
    }

    public function getProfile(Request $request)
    {
        $phone = $request->phone;

        $user = User::where('phone', $phone)->first();

        $data = [
            'name' => $user->name,
            'phone' => $user->phone,
            'email' => $user->email,
            'bday' => $user->bday,
        ];

        if ($user->avatar == null) {
            $data['avatar'] = null;
        } else {
            $data['avatar'] = Storage::url('foto_profil/' . $user->avatar);
        }

        if ($user) {
            return response([
                'status' => true,
                'message' => 'Profile retrieved successfully',
                'data' => $data
            ], 200);
        } else {
            return response([
                'status' => false,
                'message' => 'Profile retrieval failed',
                'data' => null
            ], 200);
        }
    }

    public function  updateProfile(Request $request)
    {
        // return response([
        //     'status' => false,
        //     'message' => 'Profile updated successfully',
        //     'data' => $request->phone,
        // ]);

        $phoneNum = $request->phone;
        $avatar = $request->avatar;

        if ($avatar) {
            $name = date('Y-m-d H:i:s') . '-' . $request->file('avatar')->getClientOriginalName();
            $path = Storage::putFileAs('public/foto_profil', $request->file('avatar'), $name);
            $link = Storage::url($path);

            $user = User::where('phone', $phoneNum)->first();
            $user::where('phone', $phoneNum)->update(['name' => $request->name, 'email' => $request->email, 'avatar' => $name, 'bday' => date('Y-m-d', strtotime($request->bday))]);

            $data = [
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'bday' => $user->bday,
                'avatar' => $link,
            ];

            if ($user) {
                return response([
                    'status' => true,
                    'message' => 'Profile updated successfully',
                    'data' => $data
                ], 200);
            } else {
                return response([
                    'status' => false,
                    'message' => 'Profile update failed',
                    'data' => null
                ], 200);
            }
        } else {
            $user = User::where('phone', $phoneNum)->first();
            $user::where('phone', $phoneNum)->update(['name' => $request->name, 'email' => $request->email, 'bday' => date('Y-m-d', strtotime($request->bday))]);

            $data = [
                'name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'bday' => $user->bday,
                'avatar' => null,
            ];
            if ($user) {
                return response([
                    'status' => true,
                    'message' => 'Profile updated successfully',
                    'data' => $data
                ], 200);
            } else {
                return response([
                    'status' => false,
                    'message' => 'Profile update failed',
                    'data' => null
                ], 200);
            }
        }
    }
}
