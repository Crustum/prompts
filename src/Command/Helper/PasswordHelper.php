<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\PasswordPrompt;
use Override;

/**
 * Cake Console helper for PasswordPrompt.
 *
 * Expected `$args` keys:
 * - `label` (string, required) — prompt label
 * - `placeholder` (string) — placeholder when empty
 * - `required` (bool|string) — required flag or message
 * - `validate` (mixed) — validator callback or rules
 * - `hint` (string) — hint text
 * - `transform` (\Closure|null) — value transform
 */
class PasswordHelper extends Helper
{
    /**
     * Build and run a PasswordPrompt.
     *
     * @param array<string, mixed> $args Prompt configuration (see class docblock)
     * @return mixed Prompt result
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        return (new PasswordPrompt(
            label: (string)($args['label'] ?? ''),
            placeholder: (string)($args['placeholder'] ?? ''),
            required: $args['required'] ?? false,
            validate: $args['validate'] ?? null,
            hint: (string)($args['hint'] ?? ''),
            transform: ($args['transform'] ?? null) instanceof Closure ? $args['transform'] : null,
        ))->prompt();
    }

    /**
     * Build and run a PasswordPrompt (discard return value).
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
