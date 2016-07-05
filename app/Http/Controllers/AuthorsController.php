<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;
use App\Transformer\AuthorTransformer;
use App\Author;

class AuthorsController extends Controller
{
    use ProvidesConvenienceMethods;

    /**
     * GET authors
     *
     * @return array
     */
    public function index()
    {
        $authors = Author::with('ratings')->get();
        return $this->collection($authors, new AuthorTransformer());
    }

    /**
     * GET authors/id
     *
     * @return array
     */
    public function show($id)
    {
        $author = Author::findOrFail($id);
        return $this->item($author, new AuthorTransformer());
    }

    /**
     * POST create new author
     *
     * @param  Request $request
     * @return mix
     */
    public function store(Request $request)
    {
        $this->validateAuthor($request);

        $author = Author::create($request->all());
        $data   = $this->item($author, new AuthorTransformer());

        return response()->json($data, 201, [
            'Location' => route('authors.show', ['id' => $author->id])
        ]);
    }

    /**
     * PUT update existing author by id
     *
     * @param  Request $request
     * @param  int  $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $this->validateAuthor($request);
        $author = Author::findOrFail($id);

        $author->fill($request->all());
        $author->save();

        $data = $this->item($author, new AuthorTransformer());

        return response()->json($data, 200);
    }

    /**
     * DELETE delete existing author
     *
     * @param  int $id
     * @return mixed
     */
    public function destroy($id)
    {
        Author::findOrFail($id)->delete();

        return response(null, 204);
    }

    /**
     * Validate input for author's information
     *
     * @param  Request $request
     * @return boolean
     */
    private function validateAuthor(Request $request)
    {
        $this->validate($request, [
            'name'      => 'required|max:255',
            'gender'    => [
                'required',
                'regex:/^(male|female)$/i'
            ],
            'biography' => 'required'
        ]);
    }


}
