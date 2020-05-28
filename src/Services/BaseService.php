<?php namespace Sculptor\Foundation\Services;

use Illuminate\Support\Facades\Log;
use Sculptor\Foundation\Contracts\Runner;
use Sculptor\Foundation\Contracts\RunnerResult;

class BaseService
{
    /**
     * @var Runner
     */
    protected $runner;

    public function __construct(Runner $runner)
    {
        $this->runner = $runner;
    }

    /**
     * @param string $service
     * @param string $command
     * @param string|null $param
     * @return RunnerResult
     */
    protected function service( string $service, string $command, string $param = null): RunnerResult
    {
        $command = [ $service, $command ];

        if ($param) {
            $commands[] = $param;
        }

        $result = $this->runner->run($command);

        if (!$result->success()) {
            Log::error("Command: " . join(' ', $command));
            Log::error("Code: {$result->code()}");
            Log::error("Output: {$result->output()}");
            Log::error("Error: {$result->error()}");
        }

        return $result;
    }
}
