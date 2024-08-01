<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminLoginRequest;
use App\Http\Resources\GeneralRescource;
use App\Http\Resources\UnpaidRedeemResource;
use App\Http\Utilities\CustomResponse;
use App\Models\RedeemHistory;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
    public function getUnpaidRedeem(): JsonResponse
    {
        $unpaid_redeems = RedeemHistory::select('users.first_name', 'users.last_name', 'users.email', 'users.ktp_image', 'users.account_number', 'banks.name as bank_name', 'redeem_histories.total', 'redeem_histories.id')
        ->join('users','users.id','redeem_histories.user_id')
        ->join('banks','users.bank_id','banks.id')
        ->where('status','on-process')->get();
        return (UnpaidRedeemResource::collection($unpaid_redeems))->response()->setStatusCode(200);
    }
}