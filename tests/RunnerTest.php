<?php namespace Sculptor\Foundation\Tests;

use PHPUnit\Framework\TestCase;
use Sculptor\Foundation\Exceptions\PathNotFoundException;
use Exception;
use Sculptor\Foundation\Runner\Runner;

class RunnerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @throws PathNotFoundException
     */
    public function testRunnerExistent()
    {
        $runner = new Runner();
        $result = $runner->from('/tmp')->run([ 'ls' ] );

        $this->assertTrue($result->success());
        $this->assertTrue($result->code() == 0);
    }

    public function testRunnerNotExistent()
    {
        $runner = new Runner();
        $result = $runner->from('/tmp')->run([ 'notexistent'] );

        $this->assertFalse($result->success());
        $this->assertTrue($result->code() == 127);
    }

    public function testRunnerPathNotExistent()
    {
        $this->expectException(PathNotFoundException::class);

        $runner = new Runner();
        $runner->from('/tmp-not-Exists')->run([ 'notexistent'] );
    }

    public function testRunOrFail()
    {
        $runner = new Runner();

        $result = $runner->from('/tmp')->runOrFail([ 'ls' ] );

	$this->assertTrue($result != null);
    }

    public function testRunOrFailError()
    {
        $this->expectException(Exception::class);

        $runner = new Runner();
        $runner->from('/tmp')->runOrFail([ 'ls' , '/no-existent-path' ] );
    }
}
