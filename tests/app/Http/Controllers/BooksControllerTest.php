<?php

namespace Tests\App\Http\Controllers;

use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\App\TestCase;

class BooksControllerTest extends TestCase
{
    /** @test */
    public function index_status_code_shoud_be_200()
    {
        $this->visit('/books')->seeStatusCode(200);
    }
}
