<?php namespace Sculptor\Foundation\Tests;

use PHPUnit\Framework\TestCase;
use Sculptor\Foundation\Support\Replacer;

/**
 * (c) Alessandro Cappellozza <alessandro.cappellozza@gmail.com>
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */
class ReplacerTest extends TestCase
{
    public function testReplaceFound()
    {
        $replacer = new Replacer('some text change');

        $replacer->replace('text', 'texting');

        $this->assertTrue($replacer->value() == 'some texting change');
    }

    public function testMultipleReplaceFound()
    {
        $replacer = new Replacer('some text change');

        $replacer
            ->replace('text', 'texting')
            ->replace('change', 'changing');

        $this->assertTrue($replacer->value() == 'some texting changing');
    }

    public function testReplaceNotFound()
    {
        $replacer = new Replacer('some text change');

        $replacer->replace('texting', 'text');

        $this->assertFalse($replacer->value() == 'some texting change');
    }
}
