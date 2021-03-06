<?php

namespace Tests\App\Http\Response;

use Tests\App\TestCase;
use Mockery as m;
use League\Fractal\Manager;
use App\Http\Response\FractalResponse;
use League\Fractal\Serializer\SerializerAbstract;
use Illuminate\Http\Request;

class FractalResponseTest extends TestCase
{
    /** @test */
    public function it_can_be_initialized()
    {
        $manager = m::mock(Manager::class);
        $serializer = m::mock(SerializerAbstract::class);
        $request = m::mock(Request::class);

        $manager->shouldReceive('setSerializer')
                ->with($serializer)
                ->once()
                ->andReturn($manager);

        $fractal = new FractalResponse($manager, $serializer, $request);

        $this->assertInstanceOf(FractalResponse::class, $fractal);
    }

    /** @test */
    public function it_can_transform_an_item()
    {
        $request = m::mock(Request::class);
        $transformer = m::mock('League\Fractal\TransformerAbstract');

        $scope = m::mock('League\Fractal\Scope');
        $scope->shouldReceive('toArray')
                ->once()
                ->andReturn(['foo' => 'bar']);

        $serializer = m::mock('League\Fractal\Serializer\SerializerAbstract');

        $manager = m::mock('League\Fractal\Manager');
        $manager->shouldReceive('setSerializer')
                ->with($serializer)
                ->once();

        $manager->shouldReceive('createData')
                ->once()
                ->andReturn($scope);

        $subject = new FractalResponse($manager, $serializer, $request);
        $this->assertInternalType(
            'array',
            $subject->item(['foo' => 'bar'], $transformer)
        );
    }

    /** @test */
    public function it_can_transform_a_collection()
    {
        $data = [
            ['foo' => 'bar'],
            ['fizz' => 'buzz']
        ];

        $request = m::mock(Request::class);

        $transformer = m::mock('League\Fractal\TransformerAbstract');

        $scope = m::mock('League\Fractal\Scope');
        $scope->shouldReceive('toArray')
                ->once()
                ->andReturn($data);

        $serializer = m::mock('League\Fractal\Serializer\SerializerAbstract');

        $manager = m::mock('League\Fractal\Manager');
        $manager->shouldReceive('setSerializer')
                ->with($serializer)
                ->once();

        $manager->shouldReceive('createData')
                ->once()
                ->andReturn($scope);

        $subject = new FractalResponse($manager, $serializer, $request);
        $this->assertInternalType(
            'array',
            $subject->collection($data, $transformer)
        );
    }

    /**
     * @test
     */
    public function it_should_parse_passed_includes_when_passed()
    {
        $serializer = m::mock('League\Fractal\Serializer\SerializerAbstract');

        $manager = m::mock('League\Fractal\Manager');
        $manager->shouldReceive('setSerializer')->with($serializer);
        $manager->shouldReceive('parseIncludes')->with('books');

        $request = m::mock(Request::class);
        $request->shouldNotReceive('query');

        $subject = new FractalResponse($manager, $serializer, $request);
        $subject->parseIncludes('books');
    }

    /**
     * @test
     */
    public function it_should_parse_request_query_includes_with_no_arguments()
    {
        $serializer = m::mock(SerializerAbstract::class);

        $manager = m::mock(Manager::class);
        $manager->shouldReceive('setSerializer')->with($serializer);
        $manager->shouldReceive('parseIncludes')->with('books');

        $request = m::mock(Request::class);
        $request->shouldReceive('query')
                ->with('include', '')
                ->andReturn('books');

        (new FractalResponse($manager, $serializer, $request))->parseIncludes();
    }
}

