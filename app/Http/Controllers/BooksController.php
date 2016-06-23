<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BooksController
{
    /**
     * GET /books
     *
     * @return array
     */
    public function index()
    {
        return Book::all();
    }

    /**
     * GET /books/{id}
     *
     * @param  int $id
     * @return mixed
     */
    public function show($id)
    {
        try{
            return Book::findOrFail($id);
        } catch(ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                    'message' => 'Book not found'
                ]
            ], 404);
        }
    }

}
