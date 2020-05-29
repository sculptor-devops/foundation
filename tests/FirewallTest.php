<?php namespace Sculptor\Foundation\Tests;

use mysql_xdevapi\Exception;
use PHPUnit\Framework\TestCase;
use Sculptor\Foundation\Services\Firewall;
use Sculptor\Foundation\Tests\Fixtures\RunnerStub;

class FirewallTest extends TestCase
{
    private $outputActive = "Status: active

     To                         Action      From
     --                         ------      ----
[ 1] 22/tcp                     ALLOW IN    Anywhere
[ 2] 80/tcp                     ALLOW IN    Anywhere
[ 3] 443/tcp                    ALLOW IN    Anywhere
[ 4] Nginx Full                 ALLOW IN    Anywhere
[ 5] 22/tcp (v6)                ALLOW IN    Anywhere (v6)
[ 6] 80/tcp (v6)                ALLOW IN    Anywhere (v6)
[ 7] 443/tcp (v6)               ALLOW IN    Anywhere (v6)
[ 8] Nginx Full (v6)            ALLOW IN    Anywhere (v6)
";

    public function testFirewallReset()
    {
        $runner = RunnerStub::success(['ufw', 'reset'], "");

        $firewall = new Firewall($runner);

        $this->assertTrue($firewall->reset());
    }

    public function testFirewallResetError()
    {
        $runner = RunnerStub::error(['ufw', 'reset'], "");

        $firewall = new Firewall($runner);

        $this->assertFalse($firewall->reset());
    }

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

    public function testFirewallAllow()
    {
        $runner = RunnerStub::success(['ufw', 'allow', '80'], "");

        $firewall = new Firewall($runner);

        $this->assertTrue($firewall->allow(80));
    }

    public function testFirewallAllowError()
    {
        $runner = RunnerStub::error(['ufw', 'allow', '80'], "");

        $firewall = new Firewall($runner);

        $this->assertFalse($firewall->allow(80));
    }

    public function testFirewallAllowInt()
    {
        $runner = RunnerStub::success(['ufw', 'allow', "80/tcp"], "");

        $firewall = new Firewall($runner);

        $this->assertTrue($firewall->allow(80, true));
    }

    public function testFirewallAllowIntError()
    {
        $runner = RunnerStub::error(['ufw', 'allow', "80/tcp"], "");

        $firewall = new Firewall($runner);

        $this->assertFalse($firewall->allow(80, true));
    }

    public function testFirewallStatus()
    {
        $runner = RunnerStub::success(['ufw', 'status'], $this->outputActive);

        $firewall = new Firewall($runner);

        $this->assertTrue($firewall->status());
    }

    public function testFirewallStatusError()
    {
        $runner = RunnerStub::success(['ufw', 'status'], "Status: inactive\n");

        $firewall = new Firewall($runner);

        $this->assertFalse($firewall->status());
    }

    public function testFirewallList()
    {
        $runner = RunnerStub::success(['ufw', 'status', 'numbered'],  $this->outputActive);

        $firewall = new Firewall($runner);

        $this->assertTrue(count($firewall->list()) == 8);
    }

    public function testFirewallDeny()
    {
        $runner = RunnerStub::success(['ufw', 'deny', 'from', '1.2.3.4', 'to', 'any', 'port', '80'], "");

        $firewall = new Firewall($runner);

        $this->assertTrue($firewall->deny( '1.2.3.4',80));
    }

    /* public function testFirewallDenyError()
    {
        $runner = RunnerStub::error(['ufw', 'deny', 'from', '80'], "");

        $firewall = new Firewall($runner);

        $this->assertFalse($firewall->deny(80));
    }*/


}
