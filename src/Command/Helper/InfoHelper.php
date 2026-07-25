<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Console\Helper;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\Note;
use Override;

/**
 * Cake Console helper for info notes.
 *
 * Expected `$args` keys:
 * - `message` (string, required) — info message
 */
class InfoHelper extends Helper
{
    /**
     * Display an info note.
     *
     * @param array<string, mixed> $args Note configuration (see class docblock)
     * @return mixed Always null
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        (new Note((string)($args['message'] ?? ''), 'info'))->display();

        return null;
    }

    /**
     * Display an info note.
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
