<?php namespace Sculptor\Foundation\Tests;

use PHPUnit\Framework\TestCase;
use Sculptor\Foundation\Services\Firewall;
use Sculptor\Foundation\Tests\Fixtures\RunnerStub;

class FirewallTest extends TestCase
{
    public function testFirewallActivate()
    {
        $runner = RunnerStub::success(['ufw', '--force', 'enable'], "active\n");

        $firewall = new Firewall($runner);

        $this->assertTrue($firewall->enable());
    }

    public function testFirewallActivateError()
    {
        $runner = RunnerStub::error(['ufw', '--force', 'enable'], "active\n");

        $firewall = new Firewall($runner);

        $this->assertFalse($firewall->enable());
    }

    public function testFirewallDisable()
    {
        $runner = RunnerStub::success(['ufw', '--force', 'disable'], "active\n");

        $firewall = new Firewall($runner);

        $this->assertTrue($firewall->disable());
    }

    public function testFirewallDisableError()
    {
        $runner = RunnerStub::error(['ufw', '--force', 'disable'], "active\n");

        $firewall = new Firewall($runner);

        $this->assertFalse($firewall->disable());
    }


}
