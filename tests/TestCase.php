<?php

namespace Tests\App;
use Laravel\Lumen\Testing\Concerns\MakesHttpRequests;

class TestCase extends \Laravel\Lumen\Testing\TestCase
{
    use MakesHttpRequests;

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
}
