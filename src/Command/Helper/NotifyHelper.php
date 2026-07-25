<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Console\Helper;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\NotifyPrompt;
use Override;

/**
 * Cake Console helper for NotifyPrompt.
 *
 * Expected `$args` keys:
 * - `title` (string, required) — notification title
 * - `body` (string) — notification body
 * - `subtitle` (string) — macOS subtitle
 * - `sound` (string) — macOS sound name
 * - `icon` (string) — Linux icon path
 */
class NotifyHelper extends Helper
{
    /**
     * Send a desktop notification.
     *
     * @param array<string, mixed> $args Notification configuration (see class docblock)
     * @return mixed Whether delivery succeeded
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        return (new NotifyPrompt(
            title: (string)($args['title'] ?? ''),
            body: (string)($args['body'] ?? ''),
            subtitle: (string)($args['subtitle'] ?? ''),
            sound: (string)($args['sound'] ?? ''),
            icon: (string)($args['icon'] ?? ''),
        ))->prompt();
    }

    /**
     * Send a desktop notification.
     *
     * @param array<string, mixed> $args Notification configuration (see class docblock)
     * @return void
     */
    #[Override]
    public function output(array $args): void
    {
        $this->run($args);
    }
}
