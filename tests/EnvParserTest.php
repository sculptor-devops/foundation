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
        File::shouldReceive('get')
            ->with('some.quoted.file')
            ->andReturn('test="123456"');

        $env = new EnvParser('some.quoted.file');

        $value = $env->get('test');

        $this->assertTrue($value == '123456');
    }

    public function testGetUnQuoted()
    {
        File::shouldReceive('get')
            ->with('some.unquoted.file')
            ->andReturn('test="123456"');

        $env = new EnvParser('some.unquoted.file');

        $value = $env->get('test', false);

        $this->assertTrue($value == '"123456"');
    }

    /**
     * @throws \Exception
     */
    public function testPutUnquoted()
    {
        File::shouldReceive('get')
            ->with('some.other.file')
            ->andReturn('test=123456');

        File::shouldReceive('put')
            ->with('some.other.file', 'test=78910')
            ->andReturnTrue();

        $env = new EnvParser('some.other.file');

        $this->assertTrue($env->set('test' , '78910', false));
    }

    /**
     * @throws \Exception
     */

    public function testPutQuoted()
    {
        File::shouldReceive('get')
            ->with('some.other.unquoted.file')
            ->andReturn('test="123456"');

        File::shouldReceive('put')
            ->with('some.other.unquoted.file', 'test="78910"')
            ->andReturnTrue();

        $env = new EnvParser('some.other.unquoted.file');

        $this->assertTrue($env->set('test' , '78910'));
    }
}
