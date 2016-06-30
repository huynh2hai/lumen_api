<?php

namespace Tests\App\Http\Controllers;

use Tests\App\TestCase;
use Illuminate\Http\Response;

class BooksControllerValidationTest extends TestCase
{
    /** @test */
    public function it_validates_required_fields_when_creating_a_new_book()
    {
        $this->post('/books', [], ['Accept' => 'application/json']);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->response->getSTatusCode());

        $body = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('error', $body);
    }

    /** @test */
    public function it_validates_required_fields_when_updating_a_book()
    {
        $book = factory(\App\Book::class)->create();
        $this->put("/books/{$book->id}", [], ['Accept' => 'application/json']);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $this->response->getSTatusCode());

        $body = json_decode($this->response->getContent(), true);

        $this->assertArrayHasKey('error', $body);

    }

    /** @test */
    public function title_fails_create_validation_when_just_too_long()
    {
        $book = factory(\App\Book::class)->make();
        $book->title = str_repeat('a', 256);

        $this->post('/books', $book->toArray(), ['Accept' => 'application/json']);

        $this->seeStatusCode(Response::HTTP_BAD_REQUEST)
             ->seeJson(['error' => ['message' => 'The given data failed to pass validation.', 'status' => 400]])
             ->notSeeInDatabase('books', ['title' => $book->title]);
    }

    /** @test */
    public function title_fails_update_validation_when_just_too_long()
    {
        $book = factory(\App\Book::class)->create();
        $book->title = str_repeat('a', 256);

        $this->put("/books/$book->id", $book->toArray(), ['Accept' => 'application/json']);

        $this->seeStatusCode(Response::HTTP_BAD_REQUEST)
             ->seeJson(['error' => ['message' => 'The given data failed to pass validation.', 'status' => 400]])
             ->notSeeInDatabase('books', ['title' => $book->title]);
    }
}
