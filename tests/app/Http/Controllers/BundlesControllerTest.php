<?php

namespace Tests\App\Http\Controllers;

use Tests\App\TestCase;

class BundlesControllerTest extends TestCase
{
    /** @test */
    public function show_should_return_a_valid_bundle()
    {
        $bundle = $this->bundleFactory();

        $this->get("/bundles/{$bundle->id}", ['Accept' => 'application/json']);

        $this->seeStatusCode(200);

        $body = $this->response->getData(true);

        $this->assertArrayHasKey('data', $body);
        $data = $body['data'];
    }

    /** @test */
    public function addBook_should_add_a_book_to_a_bundle()
    {
        $bundle = factory(\App\Bundle::class)->create();
        $book = $this->bookFactory();

        $this->notSeeInDatabase('book_bundle', ['bundle_id' => $bundle->id]);

        $this->put("/bundles/{$bundle->id}/books/{$book->id}", [], ['Accept' => 'application/json']);

        $this->seeStatusCode(200);

        $dbBundle = \App\Bundle::with('books')->find($bundle->id);
        $this->assertCount(1, $dbBundle->books, 'The bundle should have 1 associated book');


        $this->assertEquals($dbBundle->books()->first()->id, $book->id);

        $body = $this->response->getData(true);

        $this->assertArrayHasKey('data', $body);
        $this->assertArrayHasKey('books', $body['data']);
        $this->assertArrayHasKey('data', $body['data']['books']);

        $books = $body['data']['books'];
        $this->assertEquals($book->id, $books['data'][0]['id']);

    }

    /** @test */
    public function removeBook_should_remove_a_book_from_a_bundle()
    {
        $bundle = $this->bundleFactory(3);
        $book = $bundle->books()->first();

        $this->seeInDatabase('book_bundle', [
            'book_id' => $book->id,
            'bundle_id' => $bundle->id,
        ]);

        $this->assertCount(3, $bundle->books);

        $this->delete("/bundles/{$bundle->id}/books/{$book->id}")
             ->seeStatusCode(204)
             ->notSeeInDatabase('book_bundle', [
                    'book_id' => $book->id,
                    'bundle_id' => $bundle->id,
                ]);

        $dbBundle = \App\Bundle::find($bundle->id);
        $this->assertCount(2, $dbBundle->books);
    }
}
