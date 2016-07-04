<?php

namespace App\Http\Controllers;

use App\Bundle;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Transformer\BundleTransformer;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

class BundlesController extends Controller
{
    use ProvidesConvenienceMethods;

    /**
     * GET show specific bundle
     *
     * @param  int $id
     * @return mixed
     */
    public function show($id)
    {
        $bundle = Bundle::findOrFail($id);
        $data = $this->item($bundle, new BundleTransformer());

        return response()->json($data);
    }

    public function addBook($bundleId, $bookId)
    {
        $bundle = \App\Bundle::findOrFail($bundleId);
        $book = \App\Book::findOrFail($bookId);

        $bundle->books()->attach($book);

        $data = $this->item($bundle, new BundleTransformer());

        return response()->json($data);
    }

    public function removeBook($bundleId, $bookId)
    {
        $bundle = \App\Bundle::findOrFail($bundleId);
        $book = \App\Book::findOrFail($bookId);

        $bundle->books()->detach($book);


        return response(null, 204);
    }

}
