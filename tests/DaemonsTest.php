<?php namespace Sculptor\Foundation\Tests;

use PHPUnit\Framework\TestCase;
use Sculptor\Foundation\Services\Daemons;
use Sculptor\Foundation\Tests\Fixtures\RunnerStub;

class DaemonsTest extends TestCase
{
    public function testDaemonActive()
    {
        $runner = RunnerStub::success(['systemctl', 'is-active', 'test.service'], "active\n");

        $daemon = new Daemons($runner);

        $this->assertTrue($daemon->active('test.service'));
    }

    public function testDaemonNotActive()
    {
        $runner = RunnerStub::success(['systemctl', 'is-active', 'test.service'], "error\n");

        $daemon = new Daemons($runner);

        $this->assertFalse($daemon->active('test.service'));
    }

    public function testDaemonErrorActive()
    {
        $runner = RunnerStub::error(['systemctl', 'is-active', 'test.service'], "error\n");

        $daemon = new Daemons($runner);

        $this->assertFalse($daemon->active('test.service'));
    }

    public function testDaemonOperationsSuccess()
    {
        foreach (['reload', 'restart', 'start', 'stop', 'enable', 'disable'] as $operation) {

            $runner = RunnerStub::success(['systemctl', $operation, 'test.service'], "status");

            $daemon = new Daemons($runner);

            $this->assertTrue($daemon->{$operation}('test.service'));
        }
    }

    public function testDaemonOperationsError()
    {
        foreach (['reload', 'restart', 'start', 'stop', 'enable', 'disable'] as $operation) {

            $runner = RunnerStub::error(['systemctl', $operation, 'test.service'], "status error");

            $daemon = new Daemons($runner);

            $this->assertFalse($daemon->{$operation}('test.service'));
        }
    }

    public function testDaemonInstalled()
    {
        $runner = RunnerStub::success(['dpkg', '-s', 'test.service'], "status");

        $daemon = new Daemons($runner);

        $this->assertTrue($daemon->installed('test.service'));
    }

    public function testDaemonNotInstalled()
    {
        $runner = RunnerStub::error(['dpkg', '-s', 'test.service'], "status");

        $daemon = new Daemons($runner);

        $this->assertFalse($daemon->installed('test.service'));
    }
}
