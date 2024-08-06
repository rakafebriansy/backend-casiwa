<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserDetailDeleteRequest;
use App\Http\Requests\UserDetailEditRequest;
use App\Http\Requests\UserDetailRequest;
use App\Http\Requests\UserDetailStoreRequest;
use App\Http\Resources\GeneralRescource;
use App\Http\Resources\RedeemHistoryResource;
use App\Http\Resources\UserDetailResource;
use App\Http\Utilities\CustomResponse;
use App\Models\Bank;
use App\Models\RedeemHistory;
use App\Models\StudyProgram;
use App\Models\University;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
    public function storeUniversities(UserDetailStoreRequest $request): JsonResponse
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
    public function editUniversities(UserDetailEditRequest $request): JsonResponse
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
    public function deleteUniversities(UserDetailDeleteRequest $request): JsonResponse
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
    public function storeStudyPrograms(UserDetailStoreRequest $request): JsonResponse
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
    public function editStudyPrograms(UserDetailEditRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $result = StudyProgram::where('id',$data['id'])->update([
                'name' => $request->name
            ]);

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data program studi berhasil diperbarui' : 'Data program studi gagal diperbarui';
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
    public function deleteStudyPrograms(UserDetailDeleteRequest $request): JsonResponse
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
    public function storeBanks(UserDetailStoreRequest $request): JsonResponse
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
    public function editBanks(UserDetailEditRequest $request): JsonResponse
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
    public function deleteBanks(UserDetailDeleteRequest $request): JsonResponse
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
            $balance = Auth::user()->balance;

            $response = new CustomResponse();
            $response->success = true;
            $response->message = 'Data saldo berhasil didapatkan';
            $response->data = $balance;
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
    public function getRedeemHistories()
    {
        $user_id = Auth::user()->id;
        $redeem_histories = RedeemHistory::select('redeem_histories.id','redeem_histories.updated_at as datetime','redeem_histories.total','redeem_histories.status')
        ->where('user_id',$user_id)->get();
        return (RedeemHistoryResource::collection($redeem_histories))->response()->setStatusCode(200);
    }
}
