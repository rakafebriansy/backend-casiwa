<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function pdfView()
    {
        return view('pdf-viewer');
    }
}
