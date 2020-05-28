<?php namespace Sculptor\Foundation\Services;

use Illuminate\Support\Facades\Log;
use Sculptor\Foundation\Contracts\Runner;
use Sculptor\Foundation\Contracts\Response;

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
     * @return Response
     */
    protected function service( string $service, string $command, string $param = null): Response
    {
        $command = [ $service, $command ];

        if ($param) {
            $commands[] = $param;
        }

        return  $this->runner->run($command);
    }
}
