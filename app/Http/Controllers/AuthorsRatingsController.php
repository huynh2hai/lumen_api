<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transformer\RatingTransformer;
use App\Author;

class AuthorsRatingsController extends Controller
{
    public function store(Request $request, $authorId)
    {
        $author = Author::findOrFail($authorId);
        $rating = $author->ratings()->create(['value' => $request->get('value')]);
        $data   = $this->item($rating, new RatingTransformer());

        return response()->json($data, 201);
    }

    public function destroy($authorId, $ratingId)
    {
        $author = Author::findOrFail($authorId);
        $author->ratings()->findOrFail($ratingId)->delete();

        return response()->json(null, 204);
    }
}
