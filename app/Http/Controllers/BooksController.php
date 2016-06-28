<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
        return Book::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        try{
            $book = Book::findOrFail($id);
        }catch(ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                        'message' => 'Book not found'
                    ]
                ], 404);
        }
        $book->fill($request->all());
        $book->save();

        return $book;
    }

    /**
     * POST /books
     *
     * @param  Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $book = Book::create($request->all());

        return response()->json(['created' => true], 201, [
            'Location' => route('books.show', ['id' => $book->id])
        ]);
    }

    public function destroy($id)
    {
         try{
            $book = Book::findOrFail($id);
        }catch(ModelNotFoundException $e) {
            return response()->json([
                'error' => [
                        'message' => 'Book not found'
                    ]
                ], 404);
        }

        $book->delete();

        return response(null, 204);
    }

}
