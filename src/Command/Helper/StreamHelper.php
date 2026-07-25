<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Console\Helper;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\Stream;
use Override;

/**
 * Cake Console helper that returns a Stream instance.
 */
class StreamHelper extends Helper
{
    /**
     * Create a Stream.
     *
     * @param array<string, mixed> $args Unused
     * @return \Laravel\Prompts\Stream Stream instance
     */
    public function run(array $args = []): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        return new Stream();
    }

    /**
     * Create a Stream (discard return value).
     *
     * Prefer {@see run()} to obtain the stream.
     *
     * @param array<string, mixed> $args Unused
     * @return void
     */
    #[Override]
    public function output(array $args): void
    {
        $this->run($args);
    }
}
