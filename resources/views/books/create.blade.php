<x-app-layout>
    <x-slot name="header">
        {{ __('Create Book') }}
    </x-slot>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif


    <div class="p-4 bg-white rounded-lg shadow-xs">
        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="book_name" class="block text-sm font-medium text-gray-700">Book Name</label>
                <input type="text" name="book_name" id="book_name" value="{{ old('book_name') }}"
                    class="mt-1 block w-full rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="author" class="block text-sm font-medium text-gray-700">Author</label>
                <input type="text" name="author" id="author" value="{{ old('author') }}"
                    class="mt-1 block w-full rounded-md shadow-sm" required>
            </div>
            <div class="mb-4">
                <label for="book_cover" class="block text-sm font-medium text-gray-700">Book Cover</label>
                <input type="file" name="book_cover" id="book_cover" class="mt-1 block w-full">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Create</button>
            </div>
        </form>
    </div>
</x-app-layout>
