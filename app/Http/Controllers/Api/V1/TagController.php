<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class TagController extends ApiController
{
    use ApiResponses;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'tag' => 'nullable|string',
        ]);

        $tags = [];

        if ($request->has('tag')) {
            $tags = Tag::where('tag', 'like', '%' . $request->input('tag') . '%')->get();
        }else{
            $tags = Tag::all();
        }

        return $this->ok('success', [
            'tags' => TagResource::collection($tags),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (! $request->user()->isAdmin) {
            return $this->notAuthorized('unauthorized');
        }
        
        $request->validate([
            'tag' => 'required|string|unique:tags,tag',
        ]);

        $tag = Tag::create([
            'tag' => $request->input('tag'),
        ]);
    
        return $this->ok('success', [
            'tag' => $tag,
        ]);
    }
}
