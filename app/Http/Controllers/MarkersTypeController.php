<?php

namespace App\Http\Controllers;

use App\MarkerType;
use Illuminate\Http\Request;

class MarkersTypeController extends Controller
{
    public function index()
    {
        $types = MarkerType::all();
        return response()->json($types, 200);
    }
}
