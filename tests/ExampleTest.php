<?php

namespace Sculptor\Fooundation\Tests;

use Orchestra\Testbench\TestCase;
use Sculptor\Fooundation\FooundationServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [FooundationServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
