@extends('layouts.app')

@section('content')
    @php
        $user = auth()->user();
        $profileInitial = account_initial($user->full_name);
    @endphp

    <section class="pt-28 pb-24 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-6 space-y-8">
            <div class="profile-hero">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="profile-avatar">
                            {{ $profileInitial }}
                        </div>
                        <div>
                            <p class="detail-kicker detail-kicker-dark">Update Profile</p>
                            <h2 class="profile-name">{{ $user->full_name }}</h2>
                            <p class="profile-meta">{{ $user->email }}</p>
                            <p class="profile-role">{{ ucfirst($user->role) }}</p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('my_profile.show') }}" class="profile-action-btn">
                            <i class="fa-solid fa-arrow-left"></i>
                            Back to Profile
                        </a>
                        <a href="{{ route('attractions.index') }}" class="profile-action-btn profile-action-btn-primary">
                            <i class="fa-solid fa-compass"></i>
                            Explore
                        </a>
                    </div>
                </div>
            </div>

            <div class="profile-form-shell">
                @if(session('success'))
                    <div class="profile-alert profile-alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="profile-alert profile-alert-error">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Please fix the highlighted fields and try again.</span>
                    </div>
                @endif

                <div class="profile-form-head">
                    <div>
                        <p class="detail-kicker detail-kicker-dark">Account details</p>
                        <h3 class="profile-form-title">Keep your profile information up to date</h3>
                    </div>
                    <p class="profile-form-subtitle">
                        Update your name, email, and password from one polished form.
                    </p>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="profile-form-card">
                    @csrf

                    <div class="profile-form-grid">
                        <div class="profile-form-group">
                            <label class="profile-form-label">Full name</label>
                            <div class="profile-input-wrap">
                                <i class="fa-solid fa-user profile-input-icon"></i>
                                <input type="text"
                                       name="full_name"
                                       value="{{ old('full_name', $user->full_name) }}"
                                       class="profile-form-input">
                            </div>
                            @error('full_name')
                                <p class="profile-form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label class="profile-form-label">Email address</label>
                            <div class="profile-input-wrap">
                                <i class="fa-solid fa-envelope profile-input-icon"></i>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       class="profile-form-input">
                            </div>
                            @error('email')
                                <p class="profile-form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="profile-form-divider"></div>

                    <div class="profile-form-grid">
                        <div class="profile-form-group">
                            <label class="profile-form-label">New password</label>
                            <div class="profile-input-wrap">
                                <i class="fa-solid fa-key profile-input-icon"></i>
                                <input type="password"
                                       name="password"
                                       placeholder="Leave blank to keep current password"
                                       class="profile-form-input">
                            </div>
                            @error('password')
                                <p class="profile-form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="profile-form-group">
                            <label class="profile-form-label">Confirm password</label>
                            <div class="profile-input-wrap">
                                <i class="fa-solid fa-shield-halved profile-input-icon"></i>
                                <input type="password"
                                       name="password_confirmation"
                                       class="profile-form-input">
                            </div>
                        </div>
                    </div>

                    <div class="profile-form-footer">
                        <a href="{{ route('my_profile.show') }}" class="profile-secondary-btn">Cancel</a>
                        <button type="submit" class="profile-primary-btn">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
