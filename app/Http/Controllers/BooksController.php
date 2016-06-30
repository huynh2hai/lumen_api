<?php

namespace App\Http\Controllers;

use App\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Transformer\BookTransformer;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class BooksController extends Controller
{
    use ProvidesConvenienceMethods;

    /**
     * GET /books
     *
     * @return array
     */
    public function index()
    {
        return $this->collection(Book::all(), new BookTransformer());
    }

    /**
     * GET /books/{id}
     *
     * @param  int $id
     * @return mixed
     */
    public function show($id)
    {
        return $this->item(Book::findOrFail($id), new BookTransformer());
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

        $this->validate($request, [
            'title'       => 'required|max:255',
            'description' => 'required',
            'author'      => 'required',
        ]);

        $book->fill($request->all());
        $book->save();

        return $this->item($book, new BookTransformer());
    }

    /**
     * POST /books
     *
     * @param  Request $request
     * @return json
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'       => 'required|max:255',
            'description' => 'required',
            'author'      => 'required',
        ]);

        $book = Book::create($request->all());
        $data = $this->item($book, new BookTransformer());

        return response()->json($data, 201, [
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
