<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class BooksTableSeeder extends Seeder
{
    /**
     * Seed example data for table books
     *
     * @return void
     */
    public function run()
    {

        factory(App\Author::class, 10)->create()->each(function($author) {
            $booksCount = rand(1, 5);

            while($booksCount > 0) {
                $author->books()->save(factory(App\Book::class)->make());
                $booksCount--;
            }
        });
    }
}
