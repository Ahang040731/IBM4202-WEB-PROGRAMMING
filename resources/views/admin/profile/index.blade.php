@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="admin-profile-wrapper">
    <div class="profile-card">
        <h2 class="profile-title">👤 Admin Profile</h2>

        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Username -->
            <div class="form-row">
                <label>Username</label>
                <input type="text" name="username" value="{{ $admin->username }}">
            </div>

            <!-- Phone -->
            <div class="form-row">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ $admin->phone }}">
            </div>

            <!-- Address -->
            <div class="form-row">
                <label>Address</label>
                <input type="text" name="address" value="{{ $admin->address }}">
            </div>

            <!-- Photo -->
            <div class="form-row">
                <label>Photo</label>
                <div class="custom-file-wrapper">
                    <button type="button" class="file-btn">Choose File</button>
                    <span class="file-name">No file chosen</span>
                    <input type="file" name="photo" class="real-file-input">
                </div>
                @if($admin->photo)
                    <img src="{{ asset('storage/'.$admin->photo) }}" class="profile-photo">
                @endif
            </div>

            <!-- Button -->
            <div class="text-center">
                <button type="submit" class="submit-btn">Update Profile</button>
            </div>
        </form>
    </div>
</div>

{{-- ======= FORMAL DESIGN STYLING ======= --}}
<style>
    body {
        background-color: #f4f6f9 !important;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .admin-profile-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 40px 15px;
    }

    .profile-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 50px 60px;
        width: 100%;
        max-width: 600px;
        transition: all 0.3s ease;
    }

    .profile-card:hover {
        box-shadow: 0 6px 24px rgba(0,0,0,0.12);
    }

    .profile-title {
        text-align: center;
        color: #0d6efd;
        font-weight: 600;
        margin-bottom: 40px;
    }

    .form-row {
        display: flex;
        flex-direction: column;
        margin-bottom: 25px;
    }

    .form-row label {
        font-weight: 600;
        color: #555;
        margin-bottom: 8px;
    }

    .form-row input[type="text"] {
        border: 1px solid #d0d7de;
        border-radius: 8px;
        padding: 12px 14px;
        font-size: 15px;
        transition: all 0.2s ease;
        background-color: #f9fafc;
    }

    .form-row input[type="text"]:focus {
        border-color: #0d6efd;
        background-color: #fff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
    }

    .custom-file-wrapper {
        display: flex;
        align-items: center;
        border: 1px solid #d0d7de;
        border-radius: 8px;
        overflow: hidden;
        background-color: #f9fafc;
        height: 44px;
    }

    .file-btn {
        flex: 0 0 120px;
        background-color: #e9ecef;
        border: none;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        padding: 0 12px;
        height: 100%;
    }

    .file-btn:hover {
        background-color: #dde2e6;
    }

    .file-name {
        flex: 1;
        padding: 0 12px;
        font-size: 14px;
        color: #555;
        border-left: 1px solid #d0d7de; /* 中间竖线 */
    }

    .real-file-input {
        display: none; /* 隐藏原生文件输入 */
    }

    .profile-photo {
        display: block;
        margin: 15px auto 0 auto;
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .submit-btn {
        background-color: #0d6efd;
        color: #fff;
        font-weight: 600;
        border: none;
        padding: 12px 40px;
        border-radius: 50px;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(13, 110, 253, 0.25);
    }

    .submit-btn:hover {
        background-color: #084dc1;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.35);
    }

    .alert {
        border-radius: 8px;
        font-size: 14px;
    }

    @media (max-width: 576px) {
        .profile-card {
            padding: 30px 25px;
        }
    }
</style>

<script>
    const realFileInput = document.querySelector(".real-file-input");
    const fileBtn = document.querySelector(".file-btn");
    const fileName = document.querySelector(".file-name");

    fileBtn.addEventListener("click", () => {
        realFileInput.click();
    });

    realFileInput.addEventListener("change", () => {
        if (realFileInput.files.length > 0) {
            fileName.textContent = realFileInput.files[0].name;
        } else {
            fileName.textContent = "No file chosen";
        }
    });
</script>
@endsection
