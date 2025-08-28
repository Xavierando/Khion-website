<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'tag' => 'nullable|string',
        ]);

        if ($request->has('tag')) {
            $tags = Tag::where('tag', 'like', '%'.$request->input('tag').'%')->get();
        } else {
            $tags = [];
        }

        return response()->json([
            'tags' => $tags,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tag' => 'required|string|unique:tags,tag',
        ]);

        $tag = Tag::create([
            'tag' => $request->input('tag'),
        ]);

        return response()->json([
            'tag' => $tag,
        ]);
    }
}
