<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\NumberPrompt;
use Override;

/**
 * Cake Console helper for NumberPrompt.
 *
 * Expected `$args` keys:
 * - `label` (string, required) — prompt label
 * - `placeholder` (string) — placeholder when empty
 * - `default` (string) — initial value
 * - `required` (bool|string) — required flag or message
 * - `validate` (mixed) — validator callback or rules
 * - `hint` (string) — hint text
 * - `transform` (\Closure|null) — value transform
 * - `min` (int|null) — minimum value
 * - `max` (int|null) — maximum value
 * - `step` (int|null) — step increment
 */
class NumberHelper extends Helper
{
    /**
     * Build and run a NumberPrompt.
     *
     * @param array<string, mixed> $args Prompt configuration (see class docblock)
     * @return mixed Prompt result
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        return (new NumberPrompt(
            label: (string)($args['label'] ?? ''),
            placeholder: (string)($args['placeholder'] ?? ''),
            default: (string)($args['default'] ?? ''),
            required: $args['required'] ?? false,
            validate: $args['validate'] ?? null,
            hint: (string)($args['hint'] ?? ''),
            transform: ($args['transform'] ?? null) instanceof Closure ? $args['transform'] : null,
            min: array_key_exists('min', $args) && $args['min'] !== null ? (int)$args['min'] : null,
            max: array_key_exists('max', $args) && $args['max'] !== null ? (int)$args['max'] : null,
            step: array_key_exists('step', $args) && $args['step'] !== null ? (int)$args['step'] : null,
        ))->prompt();
    }

    /**
     * Build and run a NumberPrompt (discard return value).
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
