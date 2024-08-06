<?php

namespace App\Http\Controllers;

use App\Http\Requests\EditProfileRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\UserEditProfileRequest;
use App\Http\Requests\UserForgotPasswordRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Requests\UserResetPasswordRequest;
use App\Http\Resources\GeneralRescource;
use App\Http\Resources\LoginResource;
use App\Http\Resources\UserResource;
use App\Http\Utilities\CustomResponse;
use App\Mail\ForgotPasswordMail;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
        $user = Auth::user();
        if(isset($request->id)) {
            $exists = Order::where('note_id',$request->id)->where('user_id',$user->id)->where('status','paid')->exists();
            if($exists) {
                $response = new CustomResponse();
                $response->success = true;
                $response->data = [
                    'login' => true,
                    'bought' => true,
                ];
                $response->message = 'Allowed';
                return (new GeneralRescource($response))->response()->setStatusCode(200);
            }
        }
        $response = new CustomResponse();
        $response->success = true;
        $response->data = [
            'login' => true,
            'bought' => false,
            'free_download' => $user->free_download
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
    public function editProfile(UserEditProfileRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();
            $user = Auth::user();

            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->email = $data['email'];
            $user->starting_year = $data['starting_year'];
            $user->university_id = $data['university_id'];
            $user->study_program_id = $data['study_program_id'];
            
            if(!empty($request->bank_id)) {
                $user->bank_id = $request->bank_id;
            }
            if(!empty($request->account_number)) {
                $user->account_number = $request->account_number;
            }

            if(!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
            if(!empty($request->file('ktp_image'))) {
                $ktp_image = $request->file('ktp_image');
                $file_name = uniqid() . '.' . $ktp_image->extension();
                $ktp_image->storeAs('ktp_images',$file_name);
                $user->ktp_image = $file_name;
            }

            $user->save();

            $get_user = User::select('users.*','universities.id as university_id', 'universities.name as university_name','study_programs.id as study_program_id', 'study_programs.name as study_program_name', 'banks.id as bank_id', 'banks.name as bank_name')
            ->join('universities','universities.id','users.university_id')
            ->join('study_programs','study_programs.id','users.study_program_id')
            ->leftJoin('banks','banks.id','users.bank_id')
            ->where('users.id',$user->id)
            ->first();

            DB::commit();

            return (new UserResource($get_user))->response()->setStatusCode(201);
        } catch (\PDOException $error) {
            DB::rollBack();
            throw new HttpResponseException(response([
                'errors' => [
                    'error' => [
                        'Internal Server Error'
                    ]
                ]
            ],500)); 
        }
    }
    public function loadKTP($name): BinaryFileResponse
    {
        $path = Storage::disk('local')->path("ktp_images/$name");
    
        if (Storage::disk('local')->exists("ktp_images/$name")) {
            return response()->file($path);
        } else {
            abort(404);
        }
    }

    public function forgotPassword(UserForgotPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();

            $now = Carbon::now();
            $formatted_now = $now->format('Y-m-d H:i:s');

            $isNotAllowed = DB::table('password_reset_tokens')->where('email',$data['email'])->where('expired_at','>=',$formatted_now)->exists();
            
            if($isNotAllowed) {
                throw new HttpResponseException(response([
                    'errors' => [
                        'error' => [
                            'Email sebelumnya sudah terkirim. Mohon cek kotak masuk anda'
                        ]
                    ]
                ],400)); 
            }

            $user = User::where('email',$data['email'])->first();
            

            $token = uniqid('casiwa_',true);

            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $token,
            ]);

            $reset_link = 'https://casiwa.my.id/reset?token=' . $token;

            Mail::to($data['email'])->send(new ForgotPasswordMail($user, $reset_link));

            $response = new CustomResponse();
            $response->success = true;
            $response->message = 'Email terkirim';

            DB::commit();
            
            return (new GeneralRescource($response))->response()->setStatusCode(200);
        } catch (\PDOException $error) {
            DB::rollBack();
            throw new HttpResponseException(response([
                'errors' => [
                    'error' => [
                        'Internal Server Error'
                    ]
                ]
            ],500)); 
        }
    }
    public function getReset(Request $request): JsonResponse
    {
        try {
            if(!isset($request->token)) {
                throw new HttpResponseException(response([
                    'errors' => [
                        'error' => [
                            'Token tidak tersedia'
                        ]
                    ]
                ],400)); 
            }
    
            $exists = DB::table('password_reset_tokens')->where('token',$request->token)->exists();
            if($exists) {
                $response = new CustomResponse();
                $response->success = true;
                $response->message = 'Token terkonfirmasi';
                return (new GeneralRescource($response))->response()->setStatusCode(200);
            }
            throw new HttpResponseException(response([
                'errors' => [
                    'error' => [
                        'Token telah kadaluarsa'
                    ]
                ]
            ],400)); 
        } catch (\PDOException $error) {
            throw new HttpResponseException(response([
                'errors' => [
                    'error' => [
                        'Internal Server Error'
                    ]
                ]
            ],500)); 
        }
    }

    public function resetPassword(UserResetPasswordRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            
            $password_reset_token = DB::table('password_reset_tokens')->where('token',$data['token'])->first();
            
            $user = User::where('email',$password_reset_token->email)->first();
            $user->password = Hash::make($data['password']);
            $user->save();

            DB::table('password_reset_tokens')->where('token',$data['token'])->delete();
            
            $response = new CustomResponse();
            $response->success = true;
            $response->message = 'Kata sandi berhasil di-reset';

            DB::commit();
            return (new GeneralRescource($response))->response()->setStatusCode(201);
        } catch (\PDOException $error) {
            DB::rollBack();
            throw new HttpResponseException(response([
                'errors' => [
                    'error' => [
                        $error
                    ]
                ]
            ],500)); 
        }

    }
}
