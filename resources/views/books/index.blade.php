<x-app-layout>
    <x-slot name="header">
        {{ __('Books') }}
    </x-slot>

    <div class="p-4 bg-white rounded-lg shadow-xs">
        <div class="mb-4 flex justify-between items-center">
            <div>
                <!-- Search Form -->
                <form action="{{ route('books.index') }}" method="GET" class="flex">
                    <input type="text" name="search" placeholder="Search by name or author"
                        value="{{ request()->search }}"
                        class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition duration-150 ease-in-out">
                        Search
                    </button>
                </form>
            </div>

            <div class="flex items-center space-x-4">
                <form action="{{ route('books.import') }}" method="POST" enctype="multipart/form-data"
                    class="flex items-center">
                    @csrf
                    <input type="file" name="file" required
                        class="border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <button type="submit"
                        class="ml-2 px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-150 ease-in-out">
                        Import Books
                    </button>
                </form>

                <a href="{{ route('books.export') }}"
                    class="px-4 py-2 bg-yellow-300 text-white rounded-md hover:bg-yellow-400 transition duration-150 ease-in-out">
                    Export Books
                </a>

                <a href="{{ route('books.create') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-150 ease-in-out">
                    Create Book
                </a>
            </div>
        </div>

        <!-- Sorting Options -->
        <div class="mb-4">
            <form action="{{ route('books.index') }}" method="GET" class="flex space-x-4">
                <select name="sort_by" class="border rounded-md">
                    <option value="book_name" {{ request()->sort_by === 'book_name' ? 'selected' : '' }}>Sort by Book
                        Name</option>
                    <option value="author" {{ request()->sort_by === 'author' ? 'selected' : '' }}>Sort by Author
                    </option>
                </select>

                <select name="sort_order" class="border rounded-md">
                    <option value="asc" {{ request()->sort_order === 'asc' ? 'selected' : '' }}>Ascending</option>
                    <option value="desc" {{ request()->sort_order === 'desc' ? 'selected' : '' }}>Descending</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Sort</button>
            </form>
        </div>

        <div class="overflow-hidden mb-8 w-full rounded-lg border shadow-xs">
            <div class="overflow-x-auto w-full">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase bg-gray-50 border-b">
                            <th class="px-4 py-3">Book Name</th>
                            <th class="px-4 py-3">Author</th>
                            <th class="px-4 py-3">Book Cover</th>
                            <th class="px-4 py-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @foreach ($books as $book)
                            <tr class="text-gray-700">
                                <td class="px-4 py-3 text-sm">{{ $book->book_name }}</td>
                                <td class="px-4 py-3 text-sm">{{ $book->author }}</td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($book->book_cover)
                                        <img src="{{ asset('storage/book_covers/' . $book->book_cover) }}"
                                            alt="Book Cover" class="w-16 h-16 object-cover rounded">
                                    @else
                                        <span class="text-gray-500">No cover</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <a href="{{ route('books.edit', $book->id) }}"
                                        class="px-4 py-2 bg-yellow-300 hover:bg-yellow-400 text-white rounded-md">Edit</a>
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md"
                                            onclick="return confirm('Are you sure you want to delete this book?');">Delete</button>
                                    </form>
                                    <button data-id="{{ $book->id }}"
                                        class="view-book-details px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">View</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div
                class="px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase bg-gray-50 border-t sm:grid-cols-9">
                {{ $books->links() }}
            </div>
        </div>

        <!-- Modal for Book Details -->
        <div id="bookDetailsModal" class="fixed z-10 inset-0 overflow-y-auto hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full">
                    <div class="bg-blue-500 p-4">
                        <h3 class="text-lg leading-6 font-medium text-white">Book Details</h3>
                    </div>
                    <div class="p-4">
                        <div id="bookName" class="mb-2 text-sm font-semibold"></div>
                        <div id="bookAuthor" class="mb-2 text-sm"></div>
                        <div id="bookCover" class="mb-2"></div>
                    </div>
                    <div class="bg-gray-50 p-4 flex justify-end">
                        <button id="closeModal" class="px-4 py-2 bg-red-500 text-white rounded-md">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('bookDetailsModal');
            const closeModalButton = document.getElementById('closeModal');

            // Close the modal
            closeModalButton.addEventListener('click', () => {
                modal.classList.add('hidden');
            });

            // Open modal with AJAX data
            document.querySelectorAll('.view-book-details').forEach(button => {
                button.addEventListener('click', function() {
                    const bookId = this.getAttribute('data-id');

                    fetch(`/books/${bookId}`)
                        .then(response => response.json())
                        .then(data => {
                            document.getElementById('bookName').innerText =
                                `Book Name: ${data.book_name}`;
                            document.getElementById('bookAuthor').innerText =
                                `Author: ${data.author}`;
                            if (data.book_cover_url) {
                                document.getElementById('bookCover').innerHTML =
                                    `<img src="${data.book_cover_url}" alt="Book Cover" class="w-32 h-32 object-cover rounded">`;
                            } else {
                                document.getElementById('bookCover').innerText =
                                    'No cover available';
                            }
                            modal.classList.remove('hidden');
                        });
                });
            });
        });
    </script>

</x-app-layout>
