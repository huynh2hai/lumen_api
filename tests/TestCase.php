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
}
