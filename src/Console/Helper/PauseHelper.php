<?php
declare(strict_types=1);

namespace Crustum\Prompts\Console\Helper;

use Cake\Console\Helper;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\PausePrompt;
use Override;

/**
 * Cake Console helper for PausePrompt.
 *
 * Expected `$args` keys:
 * - `message` (string) — message shown while waiting
 */
class PauseHelper extends Helper
{
    /**
     * Build and run a PausePrompt.
     *
     * @param array<string, mixed> $args Prompt configuration (see class docblock)
     * @return mixed Prompt result
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        return (new PausePrompt(
            message: (string)($args['message'] ?? 'Press enter to continue...'),
        ))->prompt();
    }

    /**
     * Build and run a PausePrompt (discard return value).
     *
     * @param array<string, mixed> $args Prompt configuration (see class docblock)
     * @return void
     */
    #[Override]
    public function output(array $args): void
    {
        $this->run($args);
    }
}
