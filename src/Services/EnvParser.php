<?php namespace Sculptor\Foundation\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Sculptor\Foundation\Support\Replacer;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
class EnvParser
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @var array<int, string>|false
     */
    private $content = [];

    /**
     * EnvParser constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     *
     */
    private function parse(): void
    {
        $content = File::get($this->filename);

        if ($content) {
            $this->content = preg_split("/\r\n|\n|\r/", $content);

            return;
        }
    }

    /**
     * @param string $key
     * @param bool $quoted
     * @return string|null
     */
    public function get(string $key, bool $quoted = true): ?string
    {
        $this->parse();

        if (!$this->content) {

            return null;
        }

        foreach ($this->content as $line) {
            if (Str::startsWith($line, $key)) {
                $value = Str::after($line, '=');

                if ($quoted) {
                    return quoteContent($value);
                }

                return $value;
            }
        }

        return null;
    }

    public function set(string $key, string $value, bool $quoted = true): void
    {
        $this->parse();

        if (!$this->content) {

            return;
        }

        $old = $this->get($key, $quoted);

        $content = File::get($this->filename);

        $content = Replacer::make($content)
            ->replace("{$key}={$old}", "{$key}={$value}")
            ->value();

        File::put($this->filename, $content);
    }
}
