@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')
<style>
    /* Overall Page Style */
    body {
        background-color: #f5f6fa;
    }

    .profile-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        padding: 40px;
    }

    /* Bigger title */
    h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        letter-spacing: 0.5px;
    }

    label {
        color: #444;
        font-weight: 600;
    }

    input.form-control {
        border-radius: 8px;
    }

    input.form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
    }

    /* File input section with separator line */
    .file-upload-wrapper {
        display: flex;
        align-items: center;
        border: 1px solid #ccc;
        border-radius: 8px;
        overflow: hidden;
    }

    .file-upload-wrapper input[type="file"] {
        flex: 1;
        border: none;
        background: #f8f9fa;
        padding: 8px 12px;
    }

    .file-separator {
        width: 1px;
        background-color: #ccc;
        height: 38px;
    }

    .file-upload-right {
        flex: 1;
        text-align: center;
        background-color: #fff;
        padding: 8px 12px;
        color: #888;
        font-size: 0.9rem;
    }

    .file-preview img {
        border-radius: 8px;
        width: 100px;
        border: 1px solid #ccc;
        margin-top: 10px;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 8px;
        padding: 10px 30px;
        font-weight: 600;
    }

    .btn-primary:hover {
        background-color: #0069d9;
    }
</style>

<div class="container mt-5">
    <h2 class="mb-5 text-center">👤 Admin Profile</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card profile-card mx-auto" style="max-width: 600px;">

            <!-- Username -->
            <div class="row mb-4 justify-content-center align-items-center">
                <label class="col-4 col-form-label fw-bold text-end">Username</label>
                <div class="col-6">
                    <input type="text" name="username" class="form-control text-center border border-secondary" value="{{ $admin->username }}">
                </div>
            </div>

            <!-- Phone -->
            <div class="row mb-4 justify-content-center align-items-center">
                <label class="col-4 col-form-label fw-bold text-end">Phone</label>
                <div class="col-6">
                    <input type="text" name="phone" class="form-control text-center border border-secondary" value="{{ $admin->phone }}">
                </div>
            </div>

            <!-- Address -->
            <div class="row mb-4 justify-content-center align-items-center">
                <label class="col-4 col-form-label fw-bold text-end">Address</label>
                <div class="col-6">
                    <input type="text" name="address" class="form-control text-center border border-secondary" value="{{ $admin->address }}">
                </div>
            </div>

            <!-- Photo -->
            <div class="row mb-4 justify-content-center align-items-center">
                <label class="col-4 col-form-label fw-bold text-end">Photo</label>
                <div class="col-6">
                    <div class="file-upload-wrapper">
                        <input type="file" name="photo" class="form-control">
                        <div class="file-separator"></div>
                        <div class="file-upload-right">No file chosen</div>
                    </div>
                    @if($admin->photo)
                        <div class="file-preview text-center">
                            <img src="{{ asset('storage/'.$admin->photo) }}" class="mt-2 d-block mx-auto">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Submit Button -->
            <div class="row justify-content-center">
                <div class="col-10 text-center">
                    <button type="submit" class="btn btn-primary px-5">Update Profile</button>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection
