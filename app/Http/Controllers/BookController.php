<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Exports\BooksExport;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Imports\BooksImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Book::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where('book_name', 'like', '%' . $request->search . '%')
                ->orWhere('author', 'like', '%' . $request->search . '%');
        }

        $sortBy = $request->get('sort_by', 'book_name');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $books = $query->paginate(10);

        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {

        if ($request->hasFile('book_cover')) {
            $fileNameWithExt = $request->file('book_cover')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $fileExtension = $request->file('book_cover')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExtension;
            $path = $request->file('book_cover')->storeAs('public/book_covers', $fileNameToStore);
        } else {
            $fileNameToStore = null;
        }

        Book::create([
            'book_name' => $request->book_name,
            'author' => $request->author,
            'book_cover' => $fileNameToStore,
        ]);

        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return response()->json([
            'book_name' => $book->book_name,
            'author' => $book->author,
            'book_cover_url' => asset('storage/book_covers/' . $book->book_cover),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {

        if ($request->hasFile('book_cover')) {
            Storage::delete('public/book_covers/'.$book->book_cover);
            $fileNameWithExt = $request->file('book_cover')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $fileExtension = $request->file('book_cover')->getClientOriginalExtension();
            $fileNameToStore = $fileName.'_'.time().'.'.$fileExtension;
            $path = $request->file('book_cover')->storeAs('public/book_covers', $fileNameToStore);
        } else {
            $fileNameToStore = $book->book_cover;
        }

        $book->update([
            'book_name' => $request->book_name,
            'author' => $request->author,
            'book_cover' => $fileNameToStore,
        ]);

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }

    public function export()
    {
        return new BooksExport();
    }

    public function import(Request $request)
    {
        Log::info('Import form accessed');
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        Excel::import(new BooksImport, $request->file('file'));

        return redirect()->route('books.index')->with('success', 'Books imported successfully.');
    }
}
