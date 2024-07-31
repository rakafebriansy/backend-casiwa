<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditProfileRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\GeneralRescource;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use App\Http\Utilities\CustomResponse;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            if(isset($request->rememberme)) {
                $response->rememberme = true;
            }
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
            $exists = Order::where('note_id',$request->id)->where('user_id',$user_id)->where('status','paid')->exists();
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
    public function profile(Request $request): JsonResponse
    {
        try {
            $user_id = Auth::user()->id;
            $user = User::select('users.*','universities.id as university_id', 'universities.name as university_name','study_programs.id as study_program_id', 'study_programs.name as study_program_name', 'banks.id as bank_id', 'banks.name as bank_name')
            ->join('universities','universities.id','users.university_id')
            ->join('study_programs','study_programs.id','users.study_program_id')
            ->leftJoin('banks','banks.id','users.bank_id')
            ->where('users.id',$user_id)
            ->first();
            
            return (new UserResource($user))->response()->setStatusCode(200);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'Email atau kata sandi salah'
                    ]
                ]
            ],401));
        }
    }
    public function editProfile(EditProfileRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        Log::info($user->password);
        $user->fill($data);
        if(!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        if(!empty($request->file('ktp_image'))) {
            $ktp_image = $request->file('ktp_image');
            $file_name = uniqid() . $ktp_image->extension();
            $ktp_image->storeAs('ktp_images',$file_name);
            $user->ktp_image = $file_name;
        }
        $result = $user->save();
        if($result) {
            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Profil Berhasil diperbarui' : 'Profil Gagal diperbarui';

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
    public function loadKTP($name)
    {
        $path = Storage::disk('local')->path("ktp_images/$name");
    
        if (Storage::disk('local')->exists("ktp_images/$name")) {
            return response()->file($path);
        } else {
            abort(404);
        }
    }
}
