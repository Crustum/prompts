<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Console\Helper;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\Callout;
use Override;

/**
 * Cake Console helper for Callout.
 *
 * Expected `$args` keys:
 * - `label` (string, required) — callout label
 * - `content` (string|array, required) — body text or structured parts
 * - `type` (string|null) — style type
 * - `info` (string) — footer info text
 */
class CalloutHelper extends Helper
{
    /**
     * Display a callout.
     *
     * @param array<string, mixed> $args Callout configuration (see class docblock)
     * @return mixed Always null
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        $content = $args['content'] ?? '';
        if (!is_string($content) && !is_array($content)) {
            $content = '';
        }

        $type = $args['type'] ?? null;

        (new Callout(
            label: (string)($args['label'] ?? ''),
            content: $content,
            type: is_string($type) ? $type : null,
            info: (string)($args['info'] ?? ''),
        ))->display();

        return null;
    }

    /**
     * Display a callout.
     *
     * @param array<string, mixed> $args Callout configuration (see class docblock)
     * @return void
     */
    #[Override]
    public function output(array $args): void
    {
        $this->run($args);
    }
}
