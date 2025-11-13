<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    // show profile
    public function show()
    {
        $account = auth()->user(); 
        $user = $account->user;
        return view('client.profile.index', compact('user'));
    }

    //update profile
    public function update(Request $request)
    {
        $account = auth()->user(); 
        $user = $account->user;

        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'photo'    => 'nullable|image|max:2048', 
        ]);

        
        if ($request->hasFile('photo')) {
            // delete photo
            if ($user->photo) {
                Storage::delete($user->photo);
            }

            $path = $request->file('photo')->store('user_photos', 'public');
            $validated['photo'] = $path;
        }

        // update
        $user->update($validated);

        return redirect()->route('client.profile.index')
                         ->with('success', 'Profile updated successfully.');
    }
}
