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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class NotesController extends Controller
{
    public function upload(UploadNoteRequest $request): JsonResponse
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();
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
            $note->price = 2500; //hardcoded
            $note->user_id = Auth::user()->id;
            $result = $note->save();
    
            $new_note = Note::select(
                'notes.id',
                'notes.title',
                'notes.thumbnail_name',
                'notes.created_at as date',
                'users.first_name',
                'users.last_name',
                'study_programs.name as study_program',
                'universities.name as university'
            )
            ->selectRaw('COUNT(orders.id) AS download_count')
            ->join('users', 'users.id', '=', 'notes.user_id')
            ->join('study_programs', 'study_programs.id', '=', 'users.study_program_id')
            ->join('universities', 'universities.id', '=', 'users.university_id')
            ->leftJoin('orders', 'notes.id', '=', 'orders.note_id')
            ->where('notes.id',$note->id)
            ->groupBy(
                'notes.id',
                'notes.title',
                'notes.thumbnail_name',
                'notes.created_at',
                'users.first_name',
                'users.last_name',
                'study_programs.name',
                'universities.name'
            )->first()->toArray();

            $response = new CustomResponse();
            $response->success = $result;
            $response->message = $result ? 'Dokumen berhasil diunggah. Silahkan refresh!' : 'Dokumen Gagal Diunggah';
            $new_note['date'] = date('d-m-Y',strtotime($new_note['date']));
            $response->data = $new_note;

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
    public function getNotePreviews(Request $request): JsonResponse
    {
        try {
            $notesQuery = Note::select(
                'notes.id',
                'notes.title',
                'notes.thumbnail_name',
                'notes.created_at',
                'users.first_name',
                'users.last_name',
                'study_programs.name as study_program',
                'universities.name as university'
            )
            ->selectRaw('COUNT(orders.id) AS download_count')
            ->join('users', 'users.id', '=', 'notes.user_id')
            ->join('study_programs', 'study_programs.id', '=', 'users.study_program_id')
            ->join('universities', 'universities.id', '=', 'users.university_id')
            ->leftJoin('orders', 'notes.id', '=', 'orders.note_id')
            ->groupBy(
                'notes.id',
                'notes.title',
                'notes.thumbnail_name',
                'notes.created_at',
                'users.first_name',
                'users.last_name',
                'study_programs.name',
                'universities.name'
            );

            if (!empty($request->university_id)) {
                $notesQuery->where('universities.id', $request->university_id);
            }

            if (!empty($request->study_program_id)) {
                $notesQuery->where('study_programs.id', $request->study_program_id);
            }

            if (!empty($request->keyword)) {
                $notesQuery->whereRaw("LOWER(notes.title) LIKE ?", ['%' . strtolower($request->keyword) . '%']);
            }

            $notes = $notesQuery->orderBy('notes.title')->get();
            $notes_wrapped = NotePreviewResource::collection($notes);
            $total_notes = $notes_wrapped->count();

            return response()->json([
                'data' => $notes_wrapped,
                'total' => $total_notes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => [
                    'data' => $e->getMessage()
                ]
            ], 500);
        }
    }
    public function getUploadedNotePreviews(Request $request): JsonResponse
    {
        try {
            $user_id = Auth::user()->id;
            
            $notesQuery = Note::select(
                'notes.id',
                'notes.title',
                'notes.thumbnail_name',
                'notes.created_at',
                'users.first_name',
                'users.last_name',
                'study_programs.name as study_program',
                'universities.name as university'
            )
            ->selectRaw('COUNT(orders.id) AS download_count')
            ->join('users', 'users.id', '=', 'notes.user_id')
            ->join('study_programs', 'study_programs.id', '=', 'users.study_program_id')
            ->join('universities', 'universities.id', '=', 'users.university_id')
            ->leftJoin('orders', 'notes.id', '=', 'orders.note_id')
            ->where('notes.user_id', $user_id);

            if (!empty($request->keyword)) {
                $notesQuery->whereRaw("LOWER(notes.title) LIKE ?", ['%' . strtolower($request->keyword) . '%']);
            }

            $notesQuery->groupBy(
                'notes.id',
                'notes.title',
                'notes.thumbnail_name',
                'notes.created_at',
                'users.first_name',
                'users.last_name',
                'study_programs.name',
                'universities.name'
            )
            ->orderBy('notes.title');

            $notes = $notesQuery->get();
            $notes_wrapped = NotePreviewResource::collection($notes);
            $total_notes = $notes_wrapped->count();

            return response()->json([
                'data' => $notes_wrapped,
                'total' => $total_notes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => [
                    'data' => 'An error occurred: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    public function getDownloadedNotePreviews(Request $request): JsonResponse
    {
        try {
            $user_id = Auth::user()->id;

            $notesQuery = Note::select(
                'notes.id',
                'notes.title',
                'notes.thumbnail_name',
                'notes.created_at',
                'users.first_name',
                'users.last_name',
                'study_programs.name as study_program',
                'universities.name as university'
            )
            ->selectRaw('COUNT(orders.id) AS download_count')
            ->join('users', 'users.id', '=', 'notes.user_id')
            ->join('study_programs', 'study_programs.id', '=', 'users.study_program_id')
            ->join('universities', 'universities.id', '=', 'users.university_id')
            ->join('orders', 'notes.id', '=', 'orders.note_id')
            ->leftJoin('orders', 'notes.id', '=', 'orders.note_id')
            ->where('orders.user_id', $user_id)
            ->where('orders.status', 'paid')
            ->groupBy(
                'notes.id',
                'notes.title',
                'notes.thumbnail_name',
                'notes.created_at',
                'users.first_name',
                'users.last_name',
                'study_programs.name',
                'universities.name'
            );

            if (!empty($request->university_id)) {
                $notesQuery->where('universities.id', $request->university_id);
            }

            if (!empty($request->study_program_id)) {
                $notesQuery->where('study_programs.id', $request->study_program_id);
            }

            if (!empty($request->keyword)) {
                $notesQuery->whereRaw("LOWER(notes.title) LIKE ?", ['%' . strtolower($request->keyword) . '%']);
            }
            
            $notes = $notesQuery->orderBy('notes.title')->get();
            $notes_wrapped = NotePreviewResource::collection($notes);
            $total_notes = $notes_wrapped->count();

            return response()->json([
                'data' => $notes_wrapped,
                'total' => $total_notes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => [
                    'data' => 'An error occurred: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    public function getSingleNote(Request $request): JsonResponse
    {
        try {
            $note = Note::select('notes.id','notes.title','notes.description','notes.file_name','notes.created_at','users.first_name','users.last_name','study_programs.name as study_program','universities.name as university')
            ->join('users','users.id','notes.user_id')
            ->join('study_programs','study_programs.id','users.study_program_id')
            ->join('universities','universities.id','users.university_id')
            ->where('notes.id',$request->id)->first();

            return (new NoteResource($note))->response()->setStatusCode(200);
        } catch (\Exception $e) {
            throw new HttpResponseException(response([
                'errors' => [
                    'data' => [
                        $e
                    ]
                ]
            ],500));
        }
    }
    public function getSingleNotePreview(Request $request): JsonResponse
    {
        try {
            $note = Note::select('notes.id','notes.title', 'notes.price', 'notes.description','notes.thumbnail_name','notes.created_at','users.first_name','users.last_name','study_programs.name as study_program','universities.name as university')
            ->join('users','users.id','notes.user_id')
            ->join('study_programs','study_programs.id','users.study_program_id')
            ->join('universities','universities.id','users.university_id')
            ->where('notes.id',$request->id)->first();

            return (new NotePreviewResource($note))->response()->setStatusCode(200);

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
        $path = Storage::disk('local')->path("pdfs/$name");
    
        if (Storage::disk('local')->exists("pdfs/$name")) {
            return response()->file($path);
        } else {
            abort(404);
        }
    }
    public function download($name)
    {
        $user = Auth::user();
        if($user) {
            $path = realpath(storage_path("app/pdfs/$name"));
            $headers = ['Content-Type: application/pdf'];
            return Response::download($path, 'test.pdf', $headers);
        }
    }
}
