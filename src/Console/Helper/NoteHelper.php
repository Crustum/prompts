<?php
declare(strict_types=1);

namespace Crustum\Prompts\Console\Helper;

use Cake\Console\Helper;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\Note;
use Override;

/**
 * Cake Console helper for Note.
 *
 * Expected `$args` keys:
 * - `message` (string, required) — note body
 * - `type` (string|null) — style type (info, warning, error, alert, intro, outro)
 */
class NoteHelper extends Helper
{
    /**
     * Display a note.
     *
     * @param array<string, mixed> $args Note configuration (see class docblock)
     * @return mixed Always null
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        $type = $args['type'] ?? null;

        (new Note(
            message: (string)($args['message'] ?? ''),
            type: is_string($type) ? $type : null,
        ))->display();

        return null;
    }

    /**
     * Display a note.
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
