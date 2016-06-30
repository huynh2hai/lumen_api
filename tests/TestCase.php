<?php

namespace Tests\App;
use Laravel\Lumen\Testing\Concerns\MakesHttpRequests;

class TestCase extends \Laravel\Lumen\Testing\TestCase
{
    use MakesHttpRequests;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate:refresh');
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    /**
     * Asserts that the response header matches a given regular expression
     *
     * @param  $header
     * @param  $regexp
     * @return $this
     */
    public function seeHeaderWithRegExp($header, $regexp)
    {
        $this->seeHeader($header)
            ->assertRegExp(
                $regexp,
                $this->response->headers->get($header)
            );

        return $this;
    }

    /**
     * Convenience method for creating a book with an author
     *
     * @param  integer $count
     * @return \App\Book
     */
    protected function bookFactory($count = 1)
    {
        $author = factory(\App\Author::class)->create();
        $books = factory(\App\Book::class, $count)->make();

        if($count === 1) {
            $books->author()->associate($author);
            $books->save();

            return $books;
        }

        foreach ($books as $book) {
            $book->author()->associate($author);
            $book->save();
        }

        return $books;
    }
}
