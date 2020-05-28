<?php namespace Sculptor\Foundation\Services;


use Illuminate\Support\Facades\Log;
use Sculptor\Contracts\Runner;
use Sculptor\Contracts\RunnerResult;

class Firewall extends BaseService
{

    /**
     * @var string
     */
    private $command = 'ufw';

    /**
     * @return bool
     */
    public function enable(): bool
    {
        return $this->service($this->command, '--force', 'enable')->success();
    }

    /**
     * @return bool
     */
    public function disable(): bool
    {
        return $this->service($this->command, '--force', 'disable')->success();
    }

    /**
     * @param string $port
     * @param bool $int
     * @return bool
     */
    public function allow(string $port, bool $int = false): bool
    {
        if (!$int) {
            return $this->service($this->command, 'allow', $port)->success();
        }

        return $this->service($this->command, 'allow', "{$port}/tcp")->success();
    }

    /**
     * @param string $port
     * @param bool $int
     * @return bool
     */
    public function deny(string $port, bool $int = false): bool
    {
        if (!$int) {
            return $this->service($this->command, 'allow', $port)->success();
        }

        return $this->service($this->command, 'deny', "{$port}/tcp")->success();
    }

    /**
     * @return bool
     */
    public function reset(): bool
    {
        return $this->service($this->command, 'reset')->success();
    }

    /**
     * @return bool
     */
    public function status(): bool
    {
        $result = $this->service($this->command, 'status');

        if ('Status: inactive' == clearNl($result->output())) {

            return false;
        }

        return true;
    }
}
