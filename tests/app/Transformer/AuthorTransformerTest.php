<?php

namespace Tests\App\Transformer;

use Tests\App\TestCase;
use App\Author;
use App\Transformer\AuthorTransformer;
use League\Fractal\TransformerAbstract;

class AuthorTransformerTest extends TestCase
{
    /** @test */
    public function it_can_be_initialized()
    {
        $subject = new AuthorTransformer();
        $this->assertInstanceOf(TransformerAbstract::class, $subject);
    }

    /**
     * @test
     */
    public function it_transforms_a_book_model()
    {
        $author    = factory(\App\Author::class)->create();
        $subject   = new AuthorTransformer();
        $transform = $subject->transform($author);

        $this->assertArrayHasKey('id', $transform);
        $this->assertArrayHasKey('name', $transform);
        $this->assertArrayHasKey('gender', $transform);
        $this->assertArrayHasKey('biography', $transform);
        $this->assertArrayHasKey('created', $transform);
        $this->assertArrayHasKey('updated', $transform);

        $this->assertEquals($author->id, $transform['id']);
        $this->assertEquals($author->name, $transform['name']);
        $this->assertEquals($author->gender, $transform['gender']);
        $this->assertEquals($author->biography, $transform['biography']);
        $this->assertEquals($author->created_at->toIso8601String(), $transform['created']);
        $this->assertEquals($author->updated_at->toIso8601String(), $transform['updated']);
    }

    /** @test */
    public function it_can_transform_related_books()
    {
        $book = $this->bookFactory();
        $author = $book->author;

        $data = (new AuthorTransformer)->includeBooks($author);

        $this->assertInstanceOf(\League\Fractal\Resource\Collection::class, $data);
    }

    /** @test */
    public function it_can_transform_an_author()
    {
        $author = factory(\App\Author::class)->create();

        $author->ratings()->save(
            factory(\App\Rating::class)->make(['value' => 5])
        );

        $author->ratings()->save(
            factory(\App\Rating::class)->make(['value' => 3])
        );

        $subject   = new AuthorTransformer();

        $actual = $subject->transform($author);

        $this->assertEquals($author->id, $actual['id']);
        $this->assertEquals($author->name, $actual['name']);
        $this->assertEquals($author->gender, $actual['gender']);
        $this->assertEquals($author->biography, $actual['biography']);
        $this->assertEquals($author->created_at->toIso8601String(), $actual['created']);
        $this->assertEquals($author->updated_at->toIso8601String(), $actual['created']);

        $this->assertArrayHasKey('rating', $actual);
        $this->assertInternalType('array', $actual['rating']);
        $this->assertEquals(4, $actual['rating']['average']);
        $this->assertEquals(5, $actual['rating']['max']);
        $this->assertEquals(80, $actual['rating']['percent']);
        $this->assertEquals(2, $actual['rating']['count']);
    }

}
