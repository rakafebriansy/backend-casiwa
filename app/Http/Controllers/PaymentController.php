<?php

namespace App\Http\Controllers;

use App\Http\Resources\GeneralRescource;
use App\Http\Utilities\CustomResponse;
use App\Models\DownloadDetail;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function getPaymentToken(Request $request): JsonResponse
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS');

        $user = Auth::user();

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $request->price ?? 2500,
            ),
            'customer_details' => array(
                'name' => $user->name,
                'email' => $user->email
            )
        );
        
        $snap_token = \Midtrans\Snap::getSnapToken($params);

        if(isset($snap_token)) {
            $response = new CustomResponse();
            $response->success = true;
            $response->message = 'Token Has Been Created';
            $response->data = [
                'snap_token' => $snap_token
            ];

            return (new GeneralRescource($response))->response()->setStatusCode(200);
        }

        throw new HttpResponseException(response([
            'errors' => [
                'error' => [
                    'Token Can\'t Be Created'
                ]
            ]
        ],500)); 
    }
    public function doPayment(Request $request)
    {
        $user_id = Auth::user()->id;
        $download_detail = new DownloadDetail();
        $download_detail->user_id = $user_id;
        $download_detail->note_id = $request->id;
        $download_detail->transaction_id = $request->transaction_id;
        $download_detail->order_id = $request->order_id;
        $result = $download_detail->save();
        Log::warning('result' . $result);
        if($result) {
            $response = new CustomResponse();
            $response->success = true;
            $response->message = 'Pembayaran Berhasil';
            return (new GeneralRescource($response))->response()->setStatusCode(200);
        }
        throw new HttpResponseException(response([
            'errors' => [
                'error' => [
                    'Payment Failed'
                ]
            ]
        ],500)); 
    }
}
