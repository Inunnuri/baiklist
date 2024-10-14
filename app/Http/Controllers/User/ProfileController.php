<?php

namespace App\Http\Controllers\User;

use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    public function form() {
        // Menyiapkan judul halaman untuk tampilan
        $title = 'Your Profile';
    
        // Mengambil pengguna yang sedang login
        $user = Auth::user();
    
        // Mengambil profil pengguna atau membuat profil baru jika belum ada
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
    
        // Mengembalikan tampilan dengan variabel yang dibutuhkan
        return view('user.profile', [
            'title' => $title,
            'user' => $user,
            'profile' => $profile
        ]);
    }
    

    public function update(Request $request){
        $request->validate([
            'name' =>'required|max:255',
            'email' =>'required|email|unique:users,email,'.Auth::id(),
            // untuk mengecek, apakah email barunya sudah pernah digunakan oleh user lain atau belum
            'phone_number' =>'required|unique:profiles,phone_number,'. Auth::user()->profile->id ?? 'NULL', //pakai ini agar pengecekan unique untuk entri lain bukan pengguna tsb, jika profil belum ada (null), pengecekan untuk uniqueness tetap bisa berjalan. 
            'address' =>'required|string|max:255',
            'region' =>'required|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->email = $request->email;
        $user->save(); //simpan perubahan email di tabel users

        // Jika profil pengguna sudah ada, ambil data profil tersebut. Jika tidak, buat instance baru dari model Profile dengan user_id yang sesuai.
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

       // Memproses foto profil jika diupload
    if ($request->hasFile('profile_photo')) {
        $file = $request->file('profile_photo');
        if ($file->isValid()) {
            // Menghapus foto profil sebelumnya jika ada
            if ($profile->profile_photo) {
                Storage::disk('public')->delete($profile->profile_photo); // Hapus file yang lama
            }
            $filePath = $file->store('profile_photos', 'public');
            $profile->profile_photo = $filePath;
        } else {
            return back()->withErrors(['profile_photo' => 'Uploaded file is not valid.']);
        }
    }
        
        $profile->fill($request->only([
            'name', 'phone_number', 'address','region',
        ]));
        // email tidak perlu di simpan di tabel profile, karena emailnya sesuai yang di tabel user

        $profile->save();

        return redirect()->route('profile', ['id' => $user->id])->with('success', 'Profile updated successfully!');
    }
}

// done
