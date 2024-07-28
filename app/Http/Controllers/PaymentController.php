<?php

namespace App\Http\Controllers;

use App\Http\Resources\GeneralRescource;
use App\Http\Utilities\CustomResponse;
use App\Models\Order;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function getPaymentToken(Request $request): JsonResponse
    {
        $user = Auth::user();
        
        $pending = Order::where('user_id',$user->id)->where('status','unpaid')->first();
        if($pending) {
            $pending->delete();
        }

        $order = new Order();
        $order->id = uniqid();
        $order->user_id = $user->id;
        $order->note_id = $request->id;
        $order->status = 'unpaid';
        $order->save();

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS');

        Log::info(json_encode($order));

        $params = array(
            'transaction_details' => array(
                'order_id' => $order->id,
                'gross_amount' => $request->price ?? 2500,
            ),
            'customer_details' => array(
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email
            ),
            'callbacks' => [
                'finish' => '/note-details/' . $request->id,
                'pending' => '/note-details/' . $request->id,
                'error' => '/note-details/' . $request->id
            ]
        );

        Log::info(json_encode($params));
        
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
    public function doPayment(Request $request): JsonResponse
    {
        Log::info($request->all());
        $server_key = env('MIDTRANS_SERVER_KEY');
        $status = $request->transaction_status;
        $hashed = hash('sha512',$request->order_id . $request->status_code . $request->gross_amount . $server_key);
        if($hashed == $request->signature_key) {
            $order = Order::find($request->order_id);
            if($status == 'capture' || $status == 'settlement') {
                $order->update([
                    'status' => 'paid',
                    'payment_type' => $request->payment_type,
                    'transaction_time' => $request->transaction_time
                ]);
            } else if ($status == 'expire' || $status == 'deny' || $status == 'cancel' || $status == 'failure'){
                $order->update([
                    'status' => 'failed',
                    'payment_type' => $request->payment_type,
                    'transaction_time' => $request->transaction_time
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'OK'
            ])->setStatusCode(200);
        }
        throw new HttpResponseException(response([
            'errors' => [
                'error' => [
                    'Payment Failed'
                ]
            ]
        ],500)); 
    }

    public function isPaid(Request $request): JsonResponse
    {
        $note_id = $request->id;
        $user_id = Auth::user()->id;
        $status = Order::where('user_id',$user_id)->where('note_id',$note_id)->first('status');
        
        if(isset($status)) {
            $response = new CustomResponse();
            $response->success = true;
            $response->message = 'Order is available';
            $response->data = [
                'status' => $status->status
            ];

            return (new GeneralRescource($response))->response()->setStatusCode(200);
        }
        throw new HttpResponseException(response([
            'errors' => [
                'error' => [
                    $request->id
                ]
            ]
        ],500)); 
        
    }
}