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

            return (new GeneralRescource($response))->response()->setStatusCode(200);
        }
        throw new HttpResponseException(response([
            'errors' => [
                'error' => [
                    'Internal Server Error'
                ]
            ]
        ],500)); 
    }

    public function getSingleNote(Request $request): JsonResponse
    {
        try {
            $notes = Note::select('notes.title','notes.description','notes.file_name','notes.created_at','users.first_name','users.last_name','study_programs.name as study_program','universities.name as university')
            ->join('users','users.id','notes.user_di')
            ->join('study_programs','study_programs.id','users.study_program_id')
            ->join('universities','universities.id','study_programs.university_id')
            ->where('notes.id',$request->get('id'))->first();
            return (NoteResource::collection($notes))->response()->setStatusCode(200);
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
    public function getNotePreviews(Request $request): JsonResponse
    {
        try {
            $notes = Note::select('notes.title','notes.thumbnail_name','notes.created_at','notes.download_count','users.first_name','users.last_name','study_programs.name as study_program','universities.name as university')
            ->join('users','users.id','notes.user_id')
            ->join('study_programs','study_programs.id','users.study_program_id')
            ->join('universities','universities.id','users.university_id')->whereRaw("LOWER(notes.title) LIKE '%". strtolower($request->keyword)."%'")->get();
            return (NotePreviewResource::collection($notes))->response()->setStatusCode(200);
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
    public function loadImagePreview($thumbnail_name)
    {
        $path = storage_path("app/thumbnails/$thumbnail_name"); ;
        if (Storage::disk('local')->exists("thumbnails/$thumbnail_name")) {
            return Response::file($path);
        } else {
            abort(404);
        }
    }
}
