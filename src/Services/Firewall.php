<?php namespace Sculptor\Foundation\Services;

use Exception;

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
            return $this->service($this->command, 'deny', 'from', $port)->success();
        }

        return $this->service($this->command, 'deny', 'from', "{$port}/tcp")->success();
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
     * @throws \Exception
     */
    public function status(): bool
    {
        $result = $this->service($this->command, 'status');

        if (!$result->success()) {
            throw new Exception("Error invoking firewall command: {$result->error()}");
        }

        $output = splitNewLine($result->output());

        if ('Status: inactive' == $output[0]) {

            return false;
        }

        if ('Status: active' == $output[0]) {

            return true;
        }

        throw new Exception("Unknown status {$output[0]}");
    }

    /**
     * @return array<string, string>
     * @throws Exception
     */
    public function list(): array
    {
        $list = [];

        $rule = '/^\[\s[0-9]{1,3}\]\s/';

        $result = $this->service($this->command, 'status', 'numbered');

        $output = splitNewLine($result->output());

        if (!$result->success()) {
            throw new Exception("Error invoking firewall command: {$result->error()}");
        }

        foreach ($output as $line) {
            if (preg_match($rule, $line)) {
               $list[] = preg_replace($rule, '', $line);
            }
        }

        return $list;
    }
}
