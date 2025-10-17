<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponses;
    //
    public function show(Request $request)
    {
        $user = $request->user();

        dd($user);

        return $this->ok('success',['user' => ($user) ? new UserResource($user) : null]);
    }
}
