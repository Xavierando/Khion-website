<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\UserResource;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends ApiController
{
    use ApiResponses;

    //
    public function update(Request $request)
    {
        $user = $request->user();
        $responseStatus = 'failed';

        if ($request->has(['name', 'role', 'bio'])) {
            $validated = $request->validate([
                'name' => 'required|string',
                'role' => 'string|nullable',
                'bio' => 'string|nullable',
            ]);
            $user->update($validated);
            $responseStatus = 'success';
        }

        if ($request->has('oldpsw', 'newpsw')) {
            $validated = $request->validate([
                'oldpsw' => 'required|string',
                'newpsw' => 'required|string|min:8',
            ]);
            if (Hash::check($validated['oldpsw'], $user->password)) {
                $user->update(['password' => Hash::make($validated['newpsw'])]);
                $responseStatus = 'success';
            }
        }

        if ($request->hasFile('pic')) {
            $request->validate([
                'pic' => 'required|image|mimes:jpg,jpeg,png|max:512',
            ]);

            $path = Storage::disk('images')->put('pic/', $request->file('pic'));
            Storage::disk('images')->delete($user->imageUrl);
            $user->update(['imageUrl' => $path]);
            $responseStatus = 'success';
        }

        return $this->ok($responseStatus, ['user' => new UserResource($user)]);
    }
}
