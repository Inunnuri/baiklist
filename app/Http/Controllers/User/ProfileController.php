<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function form(){
        $title = 'Your Profile';
        $user = Auth::user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        return view('user.profile', compact('title', 'user', 'profile'));
    }

    public function update(Request $request){
        $request->validate([
            'name' =>'required|max:255',
            'email' =>'required|email|unique:users,email,'.Auth::user()->id,
            'phone_number' =>'required|max:255',
            'address' =>'required|string|max:255',
            'region' =>'required|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        if ($request->hasFile('profile_photo')){
            $file = $request->file('profile_photo');
            if($file->isValid()){
                $filePath = $file->store('profile_photos', 'public');
                $profile->profile_photo = $filePath;

            }else{
                return back()->withErrors(['profile_photo' => 'Uploaded file is not valid.']);
            }
        }
        
        $profile->fill($request->only([
            'name', 'email', 'phone_number', 'address','region',
        ]));

        $profile->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}
