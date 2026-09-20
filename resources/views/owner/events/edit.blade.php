@extends('admin.layouts.app')

@section('role', 'Business Owner')

@section('content')
    <div class="space-y-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.25em] text-[--primary-gold]">Business Owner</p>
            <h2 class="text-3xl font-extrabold text-gray-800 mt-2">Edit Event</h2>
            <p class="text-gray-500 mt-2">Update your event details and return it to pending approval.</p>
        </div>

        @if(session('success'))
            <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('owner.events.update', $event) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('owner.events._form')
        </form>
    </div>
@endsection
