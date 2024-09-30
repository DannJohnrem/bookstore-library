<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\BooksExport;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate();

        return view('users.index', compact('users'));
    }

    // public function export()
    // {
    //     return Excel::download(new BooksExport, 'books.xlsx');
    // }
}
