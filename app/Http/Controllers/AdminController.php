<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Http\Resources\GeneralRescource;
use App\Http\Utilities\CustomResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function login(AdminLoginRequest $request): JsonResponse
    {
        $data = $request->validated();            

        if (Auth::guard('admin')->attempt(['username' => $data['email'], 'password' => $data['password']])) {
            $admin = Auth::guard('admin')->user();
            $response = new CustomResponse();
            $response->success = true;
            $response->message = "Anda berhasil masuk sebagai $admin->username";
            $token = $admin->createToken('AdminToken')->plainTextToken;
            $response->data = [
                'username' => $admin->username,
                'token' => $token
            ];
            return (new GeneralRescource($response))->response()->setStatusCode(200);
        }

        throw new HttpResponseException(response([
            "errors" => [
                "message" => [
                    "Username atau kata sandi salah"
                ]
            ]
        ],401));
    }
}