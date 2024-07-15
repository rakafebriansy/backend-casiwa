<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\ResponseResource;
use App\Http\Resources\UserResource;
use App\Http\Utilities\CustomResponse;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(UserRegisterRequest $request)
    {
        $data = $request->validated();

        if(User::where('email', $data['email'])->count() == 1){
            throw new HttpResponseException(response([
                'errors' => [
                    'email' => [
                        'Email is already registered'
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

        return (new ResponseResource($response))->response()->setStatusCode(201);
    }
}
