<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\ConfirmPrompt;
use Override;

/**
 * Cake Console helper for ConfirmPrompt.
 *
 * Expected `$args` keys:
 * - `label` (string, required) — prompt label
 * - `default` (bool) — default confirmation state
 * - `yes` (string) — affirmative label
 * - `no` (string) — negative label
 * - `required` (bool|string) — required flag or message
 * - `validate` (mixed) — validator callback or rules
 * - `hint` (string) — hint text
 * - `transform` (\Closure|null) — value transform
 */
class ConfirmHelper extends Helper
{
    /**
     * Build and run a ConfirmPrompt.
     *
     * @param array<string, mixed> $args Prompt configuration (see class docblock)
     * @return bool The confirmation result
     */
    public function run(array $args): bool
    {
        ConsoleIoFallbacks::setIo($this->_io);

        return (new ConfirmPrompt(
            label: (string)($args['label'] ?? ''),
            default: (bool)($args['default'] ?? true),
            yes: (string)($args['yes'] ?? 'Yes'),
            no: (string)($args['no'] ?? 'No'),
            required: $args['required'] ?? false,
            validate: $args['validate'] ?? null,
            hint: (string)($args['hint'] ?? ''),
            transform: ($args['transform'] ?? null) instanceof Closure ? $args['transform'] : null,
        ))->prompt();
    }

    /**
     * Build and run a ConfirmPrompt.
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
