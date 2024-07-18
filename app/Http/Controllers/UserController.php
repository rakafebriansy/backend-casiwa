<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\RegisterResource;
use App\Http\Resources\UserResource;
use App\Http\Utilities\CustomResponse;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        if(User::where('email', $data['email'])->count() == 1){
            throw new HttpResponseException(response([
                'errors' => [
                    'email' => [
                        'Email sudah terdaftar'
                    ]
                ]
            ],400));
        }

        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $result = $user->save();

        $response = new CustomResponse();
        $response->success = $result;
        $response->message = $result ? 'Akun berhasil didaftarkan' : 'Akun gagal didaftarkan';

        return (new RegisterResource($response))->response()->setStatusCode(201);
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            $auth = Auth::user();
            $response = new CustomResponse();
            $response->success = true;
            $response->message = 'Anda berhasil masuk';
            $response->token = $auth->createToken('auth_token')->plainTextToken;
            $response->email = $auth->email;
            return (new LoginResource($response))->response()->setStatusCode(200);
        }
        throw new HttpResponseException(response([
            "errors" => [
                "message" => [
                    "Email atau kata sandi salah"
                ]
            ]
        ],401));
    }
}
