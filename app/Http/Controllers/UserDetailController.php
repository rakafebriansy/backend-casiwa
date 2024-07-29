<?php

namespace App\Http\Controllers;

use App\Http\Resources\GeneralRescource;
use App\Http\Resources\UserDetailResource;
use App\Http\Utilities\CustomResponse;
use App\Models\Bank;
use App\Models\StudyProgram;
use App\Models\University;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    public function storeUniversities(Request $request): JsonResponse
    {
        try {
            $university = new University();
            $university->name = $request->name;
            $result = $university->save();

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data universitas berhasil ditambahkan' : 'Data universitas gagal ditambahkan';
            return (new GeneralRescource($response))->response()->setStatusCode(201);
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
    public function editUniversities(Request $request): JsonResponse
    {
        try {
            $university = University::find($request->id);
            $university->name = $request->new_name;
            $result = $university->save();

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data universitas berhasil diperbarui' : 'Data universitas gagal diperbarui';
            return (new GeneralRescource($response))->response()->setStatusCode(201);
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
    public function getStudyPrograms(): JsonResponse
    {
        try {
            $study_programs = StudyProgram::all();
            return (UserDetailResource::collection($study_programs))->response()->setStatusCode(200);
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
    public function editStudyPrograms(Request $request): JsonResponse
    {
        try {
            $study_program = StudyProgram::find($request->id);
            $study_program->name = $request->new_name;
            $result = $study_program->save();

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data program studi berhasil diperbarui' : 'Data program studi gagal diperbarui';
            return (new GeneralRescource($response))->response()->setStatusCode(201);
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
    public function storeStudyPrograms(Request $request): JsonResponse
    {
        try {
            $study_program = new StudyProgram();
            $study_program->name = $request->name;
            $result = $study_program->save();

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data program studi berhasil ditambahkan' : 'Data program studi gagal ditambahkan';
            return (new GeneralRescource($response))->response()->setStatusCode(201);
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
    public function getBanks(): JsonResponse
    {
        try {
            $banks = Bank::all();
            return (UserDetailResource::collection($banks))->response()->setStatusCode(200);
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
    public function storeBanks(Request $request): JsonResponse
    {
        try {
            $bank = new Bank();
            $bank->name = $request->name;
            $result = $bank->save();

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Data bank berhasil ditambahkan' : 'Data bank gagal ditambahkan';
            return (new GeneralRescource($response))->response()->setStatusCode(201);
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
}
