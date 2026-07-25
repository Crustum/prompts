<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\TextPrompt;
use Override;

/**
 * Cake Console helper for TextPrompt.
 *
 * Expected `$args` keys:
 * - `label` (string, required) — prompt label
 * - `placeholder` (string) — placeholder when empty
 * - `default` (string) — initial value
 * - `required` (bool|string) — required flag or message
 * - `validate` (mixed) — validator callback or rules
 * - `hint` (string) — hint text
 * - `transform` (\Closure|null) — value transform
 */
class TextHelper extends Helper
{
    /**
     * Build and run a TextPrompt.
     *
     * @param array<string, mixed> $args Prompt configuration (see class docblock)
     * @return string The entered text
     */
    public function run(array $args): string
    {
        ConsoleIoFallbacks::setIo($this->_io);

        return (new TextPrompt(
            label: (string)($args['label'] ?? ''),
            placeholder: (string)($args['placeholder'] ?? ''),
            default: (string)($args['default'] ?? ''),
            required: $args['required'] ?? false,
            validate: $args['validate'] ?? null,
            hint: (string)($args['hint'] ?? ''),
            transform: ($args['transform'] ?? null) instanceof Closure ? $args['transform'] : null,
        ))->prompt();
    }

    /**
     * Build and run a TextPrompt.
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
