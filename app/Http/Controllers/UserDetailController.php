<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDetailRequest;
use App\Http\Resources\GeneralRescource;
use App\Http\Resources\UserDetailResource;
use App\Http\Utilities\CustomResponse;
use App\Models\Bank;
use App\Models\Order;
use App\Models\StudyProgram;
use App\Models\University;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserDetailController extends Controller
{
    public function getUniversities(): JsonResponse
    {
        try {
            $universities = University::all();
            return (UserDetailResource::collection($universities))->response()->setStatusCode(200);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        'Data is not found'
                    ]
                ]
            ],500));
        }
    }
    public function storeUniversities(UserDetailRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = University::insert(['name' => $data['name']]);

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data universitas berhasil ditambahkan' : 'Data universitas gagal ditambahkan';
            $response->data = University::all()->toArray();
            return (new GeneralRescource($response))->response()->setStatusCode(201);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        'Internal Server Error'
                    ]
                ]
            ],500));
        }
    }
    public function editUniversities(UserDetailRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = University::where('id',$data['id'])->update([
                'name' => $request->name
            ]);

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data universitas berhasil diperbarui' : 'Data universitas gagal diperbarui';
            $response->data = University::all()->toArray();
            return (new GeneralRescource($response))->response()->setStatusCode(201);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        $e
                    ]
                ]
            ],500));
        }
    }
    public function deleteUniversities(UserDetailRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = University::where('id',$data['id'])->delete();

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data universitas berhasil dihapus' : 'Data universitas gagal dihapus';
            $response->data = University::all()->toArray();
            return (new GeneralRescource($response))->response()->setStatusCode(201);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        'Data Universitas tidak dapat dihapus'
                    ]
                ]
            ],500));
        }
    }
    public function getStudyPrograms(): JsonResponse
    {
        try {
            $study_programs = StudyProgram::all();
            return (UserDetailResource::collection($study_programs))->response()->setStatusCode(200);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        'Internal Server Error'
                    ]
                ]
            ],500));
        }
    }
    public function storeStudyPrograms(UserDetailRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = StudyProgram::insert(['name' => $data['name']]);

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data program studi berhasil ditambahkan' : 'Data program studi gagal ditambahkan';
            $response->data = StudyProgram::all()->toArray();
            return (new GeneralRescource($response))->response()->setStatusCode(201);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        'Internal Server Error'
                    ]
                ]
            ],500));
        }
    }
    public function editStudyPrograms(UserDetailRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = StudyProgram::where('id',$data['id'])->update([
                'name' => $request->name
            ]);

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data program studi berhasil diperbarui' : 'Data program studi gagal diperbarui';
            $response->data = Bank::all()->toArray();
            return (new GeneralRescource($response))->response()->setStatusCode(201);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        'Internal Server Error'
                    ]
                ]
            ],500));
        }
    }
    public function deleteStudyPrograms(UserDetailRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = StudyProgram::where('id',$data['id'])->delete();

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data program studi berhasil dihapus' : 'Data program studi gagal dihapus';
            $response->data = StudyProgram::all()->toArray();
            return (new GeneralRescource($response))->response()->setStatusCode(201);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        'Internal Server Error'
                    ]
                ]
            ],500));
        }
    }
    public function getBanks(): JsonResponse
    {
        try {
            $banks = Bank::all();
            return (UserDetailResource::collection($banks))->response()->setStatusCode(200);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        'Internal Server Error'
                    ]
                ]
            ],500));
        }
    }
    public function storeBanks(UserDetailRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = Bank::insert(['name' => $data['name']]);

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data bank berhasil ditambahkan' : 'Data bank gagal ditambahkan';
            $response->data = Bank::all()->toArray();
            return (new GeneralRescource($response))->response()->setStatusCode(201);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        'Internal Server Error'
                    ]
                ]
            ],500));
        }
    }
    public function editBanks(UserDetailRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = Bank::where('id',$data['id'])->update([
                'name' => $request->name
            ]);

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data bank berhasil diperbarui' : 'Data bank gagal diperbarui';
            $response->data = Bank::all()->toArray();
            return (new GeneralRescource($response))->response()->setStatusCode(201);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        'Internal Server Error'
                    ]
                ]
            ],500));
        }
    }
    public function deleteBanks(UserDetailRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = Bank::where('id',$data['id'])->delete();

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data bank berhasil dihapus' : 'Data bank gagal dihapus';
            $response->data = Bank::all()->toArray();
            return (new GeneralRescource($response))->response()->setStatusCode(201);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        'Internal Server Error'
                    ]
                ]
            ],500));
        }
    }
    public function getBalance(): JsonResponse
    {
        try {
            $user_id = Auth::user()->id;
            $total_balance = Order::where('user_id',$user_id)->where('status','paid')->count();

            $response = new CustomResponse();
            $response->success = isset($total_balance);
            $response->message = isset($total_balance) ? 'Data poin berhasil didapatkan' : 'Data poin gagal didapatkan';
            $response->data = $total_balance * 100000; //price is hardcoded
            return (new GeneralRescource($response))->response()->setStatusCode(200);
        } catch (\PDOException $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        'Internal Server Error'
                    ]
                ]
            ],500));
        }
    }
}
