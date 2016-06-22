<?php

namespace Tests\App\Http\Controllers;

use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\App\TestCase;

class BooksControllerTest extends TestCase
{
    /** @test */
    public function index_status_code_shoud_be_200()
    {
        $this->get('/books')->seeStatusCode(200);
    }

    /** @test */
    public function index_should_return_a_collection_of_records()
    {
        $this->get('/books')->seeJson(['title' => 'War of the Worlds'])
                            ->seeJson(['title' => 'A Wrinkle in Time']);
    }
}
