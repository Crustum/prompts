<?php
declare(strict_types=1);

namespace Crustum\Prompts\Console\Helper;

use Cake\Console\Helper;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\Clear;
use Override;

/**
 * Cake Console helper for Clear.
 */
class ClearHelper extends Helper
{
    /**
     * Clear the terminal.
     *
     * @param array<string, mixed> $args Unused
     * @return mixed Always null
     */
    public function run(array $args = []): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        if (Clear::shouldFallback()) {
            ConsoleIoFallbacks::renderClear();
        } else {
            (new Clear())->display();
        }

        return null;
    }

    /**
     * Clear the terminal.
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
