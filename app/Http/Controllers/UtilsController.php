<?php

namespace App\Http\Controllers;

use App\Http\Resources\FaqResource;
use App\Models\Faq;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UtilsController extends Controller
{
    public function getFaqs(): JsonResponse
    {
        try {
            $faqs = Faq::all();
            return (FaqResource::collection($faqs))->response()->setStatusCode(200);
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
