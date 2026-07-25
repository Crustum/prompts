<?php
declare(strict_types=1);

namespace Crustum\Prompts\Console\Helper;

use Cake\Console\Helper;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\Title;
use Override;

/**
 * Cake Console helper for Title.
 *
 * Expected `$args` keys:
 * - `title` (string, required) — terminal title text
 */
class TitleHelper extends Helper
{
    /**
     * Update the terminal title.
     *
     * @param array<string, mixed> $args Title configuration (see class docblock)
     * @return mixed Always null
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        $title = new Title((string)($args['title'] ?? ''));

        if (Title::shouldFallback()) {
            ConsoleIoFallbacks::renderTitle($title);
        } else {
            $title->display();
        }

        return null;
    }

    /**
     * Update the terminal title.
     *
     * @param array<string, mixed> $args Title configuration (see class docblock)
     * @return void
     */
    #[Override]
    public function output(array $args): void
    {
        $this->run($args);
    }
}
