<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Console\Helper;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\Note;
use Override;

/**
 * Cake Console helper for warning notes.
 *
 * Expected `$args` keys:
 * - `message` (string, required) — warning message
 */
class WarningHelper extends Helper
{
    /**
     * Display a warning note.
     *
     * @param array<string, mixed> $args Note configuration (see class docblock)
     * @return mixed Always null
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        (new Note((string)($args['message'] ?? ''), 'warning'))->display();

        return null;
    }

    /**
     * Display a warning note.
     *
     * @param array<string, mixed> $args Note configuration (see class docblock)
     * @return void
     */
    #[Override]
    public function output(array $args): void
    {
        $this->run($args);
    }
}
