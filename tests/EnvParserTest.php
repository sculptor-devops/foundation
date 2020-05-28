<?php namespace Sculptor\Foundation\Tests;

use _HumbugBox01d8f9a04075\Nette\Neon\Exception;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\TestCase;
use Sculptor\Foundation\Exceptions\PathNotFoundException;
use Sculptor\Foundation\Runner\Runner;
use Sculptor\Foundation\Services\EnvParser;

class EnvParserTest extends TestCase
{
    /**
     *
     */
    public function testGetQuoted()
    {
        File::shouldReceive('get')->with('some.file')->andReturn('test="123456"');

        $env = new EnvParser('some.file');

        $value = $env->get('test', false);

        $this->assertTrue($value == '123456');
    }

    /**
     * @throws \Exception
     */
    public function testGetUnquoted()
    {
        File::shouldReceive('get')->with('some.file')->andReturn('test=123456');

        File::shouldReceive('put')->with('some.file', 'test=78910')->andReturnTrue();

        $env = new EnvParser('some.file');

        $env->set('test' , '78910');
    }
}
