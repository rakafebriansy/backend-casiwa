<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminEditPasswordRequest;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Resources\GeneralRescource;
use App\Http\Resources\RedeemHistoryResource;
use App\Http\Resources\UnpaidRedeemResource;
use App\Http\Utilities\CustomResponse;
use App\Models\Admin;
use App\Models\RedeemHistory;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    public function getUnpaidRedeem(): JsonResponse
    {
        $unpaid_redeems = RedeemHistory::select('users.first_name', 'users.last_name', 'users.ktp_image', 'users.account_number', 'banks.name as bank_name', 'redeem_histories.total', 'redeem_histories.id')
        ->join('users','users.id','redeem_histories.user_id')
        ->join('banks','users.bank_id','banks.id')
        ->where('status','on-process')->get();
        return (UnpaidRedeemResource::collection($unpaid_redeems))->response()->setStatusCode(200);
    }
    public function redeemUser(Request $request): JsonResponse
    {
        $admin = Auth::user();
        Log::info($admin);
        if(isset($request->decision)) {
            $response = new CustomResponse();
            $response->success = true;
            if($request->decision) {
                RedeemHistory::where('id',$request->id)->update([
                    'status' => 'accepted',
                    'admin_id' => $admin->id
                ]);
                $response->message = "Berhasil menerima permintaan";
            } else {
                RedeemHistory::where('id',$request->id)->update([
                    'status' => 'denied',
                    'admin_id' => $admin->id
                ]);
                $response->message = "Berhasil menolak permintaan";
            }
            return (new GeneralRescource($response))->response()->setStatusCode(200);
        }
        throw new HttpResponseException(response([
            "errors" => [
                "error" => [
                    "Internal Server Error"
                ]
            ]
        ],500));
    }
    public function getRedeemHistories()
    {
        $redeem_histories = RedeemHistory::select('redeem_histories.id','redeem_histories.updated_at as datetime','redeem_histories.total','redeem_histories.status', 'users.first_name','users.last_name')
        ->join('users','users.id','redeem_histories.user_id')->get();
        return (RedeemHistoryResource::collection($redeem_histories))->response()->setStatusCode(200);
    }
    public function editPassword(AdminEditPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        $admin = Admin::find(Auth::user()->id);
        $admin->password = Hash::make($request->password);
        $result = $admin->save();
        if ($result) {
            $response = new CustomResponse();
            $response->success = true;
            $response->message = "Berhasil mengubah kata sandi";

            return (new GeneralRescource($response))->response()->setStatusCode(200);
        }
        throw new HttpResponseException(response([
            "errors" => [
                "error" => [
                    "Internal Server Error"
                ]
            ]
        ],500));
    }
}