<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\GeneralRescource;
use App\Http\Resources\LoginResource;
use App\Http\Utilities\CustomResponse;
use App\Models\Order;
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

        if($result) {
            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Akun berhasil didaftarkan' : 'Akun gagal didaftarkan';

            return (new GeneralRescource($response))->response()->setStatusCode(201);
        }
        throw new HttpResponseException(response([
            'errors' => [
                'error' => [
                    'Internal Server Error'
                ]
            ]
        ],500)); 
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
    public function isBought(Request $request): JsonResponse
    {
        $user_id = Auth::user()->id;
        if(isset($request->id)) {
            $exists = Order::where('note_id',$request->id)->where('user_id',$user_id)->exists();
            if($exists) {
                $response = new CustomResponse();
                $response->success = true;
                $response->data = [
                    'login' => true,
                    'bought' => true
                ];
                $response->message = 'Allowed';
                return (new GeneralRescource($response))->response()->setStatusCode(200);
            }
        }
        $response = new CustomResponse();
        $response->success = true;
        $response->data = [
            'login' => true,
            'bought' => false
        ];
        $response->message = 'Unallowed';
        return (new GeneralRescource($response))->response()->setStatusCode(200);
    }
}
