@extends('admin.layouts.app')

@section('role', 'Admin')

@section('content')

    <div class="mb-8">
        <h2 class="text-2xl font-extrabold text-gray-800">Edit Attraction</h2>
    </div>

    <form action="{{ route('admin.attractions.update', $attraction) }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white p-8 rounded-2xl shadow border border-gray-100">
        @csrf
        @method('PUT')

        @include('admin.attractions._form')

        <div class="mt-8 text-right">
            <button type="submit"
                    class="px-6 py-3 rounded-2xl bg-[--deep-green] text-white font-bold shadow hover:opacity-90 transition">
                Update Attraction
            </button>
        </div>

    </form>

@endsection
