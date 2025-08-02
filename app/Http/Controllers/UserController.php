<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //
    public function update(Request $request)
    {

        $user = $request->user();

        if ($request->has(['name', 'role', 'bio'])) {
            $validated = $request->validate([
                'name' => 'required|string',
                'role' => 'string|nullable',
                'bio' => 'string|nullable',
            ]);
            $user->update($validated);
        }

        if ($request->has('oldpsw', 'newpsw')) {
            $validated = $request->validate([
                'oldpsw' => 'required|string',
                'newpsw' => 'required|string|min:8',
            ]);
            if (Hash::check($validated['oldpsw'], $user->password)) {
                $user->update(['password' => Hash::make($validated['newpsw'])]);
            }
        }

        if ($request->hasFile('pic')) {
            $request->validate([
                'pic' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:512',
            ]);

            // $path = 'pic/' . Str::random(10) . '.png';
            $path = Storage::disk('images')->put('pic/', $request->file('pic'));
            Storage::disk('images')->delete($user->imageUrl);
            $user->update(['imageUrl' => $path]);
        }

        return redirect()->route('dashboard');
    }
}
