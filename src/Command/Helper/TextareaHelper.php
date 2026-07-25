<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\TextareaPrompt;
use Override;

/**
 * Cake Console helper for TextareaPrompt.
 *
 * Expected `$args` keys:
 * - `label` (string, required) — prompt label
 * - `placeholder` (string) — placeholder when empty
 * - `default` (string) — initial value
 * - `required` (bool|string) — required flag or message
 * - `validate` (mixed) — validator callback or rules
 * - `hint` (string) — hint text
 * - `rows` (int) — visible row count
 * - `transform` (\Closure|null) — value transform
 */
class TextareaHelper extends Helper
{
    /**
     * Build and run a TextareaPrompt.
     *
     * @param array<string, mixed> $args Prompt configuration (see class docblock)
     * @return mixed Prompt result
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        return (new TextareaPrompt(
            label: (string)($args['label'] ?? ''),
            placeholder: (string)($args['placeholder'] ?? ''),
            default: (string)($args['default'] ?? ''),
            required: $args['required'] ?? false,
            validate: $args['validate'] ?? null,
            hint: (string)($args['hint'] ?? ''),
            rows: (int)($args['rows'] ?? 5),
            transform: ($args['transform'] ?? null) instanceof Closure ? $args['transform'] : null,
        ))->prompt();
    }

    /**
     * Build and run a TextareaPrompt (discard return value).
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
