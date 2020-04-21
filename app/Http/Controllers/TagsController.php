<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Tags\Tag;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tag::paginate();

        return $tags;
    }

    public function search(Request $request, $keyword)
    {
        $tags = Tag::where('name->en', 'like', $keyword.'%')->get();

        return $tags;
    }
}
