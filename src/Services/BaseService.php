<?php namespace Sculptor\Foundation\Services;

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
     * @param string|null $first
     * @param string|null $second
     * @return Response
     */
    protected function service( string $service, string $command, string $first = null, string $second = null): Response
    {
        $command = [ $service, $command ];

        if ($first != null) {
            $command[] = $first;
        }

        if ($second != null) {
            $command[] = $second;
        }

        return  $this->runner->run($command);
    }
}
