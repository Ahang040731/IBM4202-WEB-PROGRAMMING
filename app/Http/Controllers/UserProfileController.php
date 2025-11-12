<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    // 显示用户资料
    public function show()
    {
        // 临时使用 user_id = 1
        $user = User::findOrFail(1);
        return view('client.profile.index', compact('user'));
    }

    // 更新用户资料
    public function update(Request $request)
    {
        $user = User::findOrFail(1); // 临时使用 user_id = 1

        // 验证输入
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'photo'    => 'nullable|image|max:2048', // 图片大小限制 2MB
        ]);

        // 处理上传头像
        if ($request->hasFile('photo')) {
            // 删除旧照片
            if ($user->photo) {
                Storage::delete($user->photo);
            }

            $path = $request->file('photo')->store('user_photos', 'public');
            $validated['photo'] = $path;
        }

        // 更新用户
        $user->update($validated);

        return redirect()->route('client.profile')
                         ->with('success', 'Profile updated successfully.');
    }
}
