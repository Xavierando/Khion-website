<?php

namespace App\Http\Responses\Fortify;

use App\Http\Resources\UserResource;
use App\Traits\ApiResponses;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Fortify;

class RegisterResponse implements RegisterResponseContract
{
    use ApiResponses;

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        return $request->wantsJson()
            ? $this->ok('success', ['user' => new UserResource($request->user())])
            : redirect()->intended(Fortify::redirects('register'));
    }
}
