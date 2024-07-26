<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadNoteRequest;
use App\Http\Resources\GeneralRescource;
use App\Http\Resources\NotePreviewResource;
use App\Http\Resources\NoteResource;
use App\Http\Utilities\CustomResponse;
use App\Models\Note;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Spatie\PdfToImage\Pdf;

class NotesController extends Controller
{
    public function upload(UploadNoteRequest $request): JsonResponse
    {
        $data = $request->validated();

        $pdf_doc = $request->file('file');
        $thumbnail = $request->file('thumbnail');
        $name = uniqid(mt_rand(),true);
        $file_name = $name . '.' . $pdf_doc->extension();
        $thumbnail_name = $name . '.' . $thumbnail->extension();

        $pdf_doc->storeAs('pdfs',$file_name);
        $thumbnail->storeAs('thumbnails',$thumbnail_name);

        $note = new Note($data);
        $note->title = $data['title'];
        $note->description = $data['description'];
        $note->file_name = $file_name;
        $note->thumbnail_name = $thumbnail_name;
        $note->user_id = Auth::user()->id;
        $result = $note->save();

        if($result) {
            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Dokumen berhasil diunggah' : 'Dokumen Gagal Diunggah';

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
    public function getNotePreviews(Request $request): JsonResponse
    {
        try {
            $notesQuery = Note::select('notes.id','notes.title','notes.thumbnail_name','notes.created_at','notes.download_count','users.first_name','users.last_name','study_programs.name as study_program','universities.name as university')
            ->join('users','users.id','notes.user_id')
            ->join('study_programs','study_programs.id','users.study_program_id')
            ->join('universities','universities.id','users.university_id');

            if(isset($request->university_id)) {
                $notesQuery->where('universities.id',$request->university_id);
            }

            if(isset($request->study_program_id)) {
                $notesQuery->where('study_programs.id',$request->study_program_id);
            }

            $notes = $notesQuery->whereRaw("LOWER(notes.title) LIKE '%". strtolower($request->keyword)."%'")->orderBy('title')->get();
            $notes_wrapped = NotePreviewResource::collection($notes);
            $total_notes = $notes_wrapped->count();

            return response()->json([
                'data' => $notes_wrapped,
                'total' => $total_notes
            ])->setStatusCode(200);
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
    public function getUploadedNotePreviews(Request $request): JsonResponse
    {
        try {
            $user_id = Auth::user()->id;
            $notes = Note::select('notes.id','notes.title','notes.thumbnail_name','notes.created_at','notes.download_count','users.first_name','users.last_name','study_programs.name as study_program','universities.name as university')
            ->join('users','users.id','notes.user_id')
            ->join('study_programs','study_programs.id','users.study_program_id')
            ->join('universities','universities.id','users.university_id')
            ->where('users.id',$user_id)->whereRaw("LOWER(notes.title) LIKE '%". strtolower($request->keyword)."%'")->orderBy('notes.title')->get();

            $notes_wrapped = NotePreviewResource::collection($notes);
            $total_notes = $notes_wrapped->count();

            return response()->json([
                'data' => $notes_wrapped,
                'total' => $total_notes
            ])->setStatusCode(200);
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
    public function getDownloadedNotePreviews(Request $request): JsonResponse
    {
        try {
            $user_id = Auth::user()->id;
            $notes = Note::select('notes.id','notes.title','notes.thumbnail_name','notes.created_at','users.first_name','users.last_name','study_programs.name as study_program','universities.name as university')
            ->selectRaw('COUNT(orders.id) AS download_count')
            ->join('users','users.id','notes.user_id')
            ->join('study_programs','study_programs.id','users.study_program_id')
            ->join('universities','universities.id','users.university_id')
            ->join('orders','users.id','orders.user_id')
            ->where('users.id',$user_id)
            ->groupBy('notes.id')
            ->orderBy('notes.title')->get();

            $notes_wrapped = NotePreviewResource::collection($notes);
            $total_notes = $notes_wrapped->count();

            return response()->json([
                'data' => $notes_wrapped,
                'total' => $total_notes
            ])->setStatusCode(200);
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
    public function getSingleNote(Request $request): JsonResponse
    {
        try {
            $note = Note::select('notes.title','notes.description','notes.file_name','notes.created_at','users.first_name','users.last_name','study_programs.name as study_program','universities.name as university')
            ->join('users','users.id','notes.user_id')
            ->join('study_programs','study_programs.id','users.study_program_id')
            ->join('universities','universities.id','users.university_id')
            ->where('notes.id',$request->id)->first();

            return (new NoteResource($note))->response()->setStatusCode(200);
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
    public function getSingleNotePreview(Request $request): JsonResponse
    {
        try {
            $note = Note::select('notes.id','notes.title', 'notes.description','notes.thumbnail_name','notes.created_at','notes.download_count','users.first_name','users.last_name','study_programs.name as study_program','universities.name as university')
            ->join('users','users.id','notes.user_id')
            ->join('study_programs','study_programs.id','users.study_program_id')
            ->join('universities','universities.id','users.university_id')
            ->where('notes.id',$request->id)->first();

            return (new NotePreviewResource($note))->response()->setStatusCode(200);

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
    public function loadImagePreview($name)
    {
        $path = storage_path("app/thumbnails/$name"); ;
        if (Storage::disk('local')->exists("thumbnails/$name")) {
            return Response::file($path);
        } else {
            abort(404);
        }
    }
    public function loadDocument($name)
    {
        $path = storage_path("app/pdfs/$name"); ;
        if (Storage::disk('local')->exists("pdfs/$name")) {
            return Response::file($path);
        } else {
            abort(404);
        }
    }
}
