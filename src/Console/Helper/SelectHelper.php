<?php
declare(strict_types=1);

namespace Crustum\Prompts\Console\Helper;

use Cake\Collection\Collection;
use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\SelectPrompt;
use Override;

/**
 * Cake Console helper for SelectPrompt.
 *
 * Expected `$args` keys:
 * - `label` (string, required) — prompt label
 * - `options` (array|\Cake\Collection\Collection, required) — selectable options
 * - `default` (int|string|null) — default selected value
 * - `scroll` (int) — visible option count
 * - `validate` (mixed) — validator callback or rules
 * - `hint` (string) — hint text
 * - `required` (bool|string) — required flag or message
 * - `transform` (\Closure|null) — value transform
 * - `info` (string|\Closure) — optional info text
 */
class SelectHelper extends Helper
{
    /**
     * Build and run a SelectPrompt.
     *
     * @param array<string, mixed> $args Prompt configuration (see class docblock)
     * @return mixed Prompt result
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        $options = $args['options'] ?? [];
        if ($options instanceof Collection) {
            $options = $options->toArray();
        }

        if (!is_array($options)) {
            $options = [];
        }

        return (new SelectPrompt(
            label: (string)($args['label'] ?? ''),
            options: $options,
            default: $args['default'] ?? null,
            scroll: (int)($args['scroll'] ?? 5),
            validate: $args['validate'] ?? null,
            hint: (string)($args['hint'] ?? ''),
            required: $args['required'] ?? true,
            transform: ($args['transform'] ?? null) instanceof Closure ? $args['transform'] : null,
            info: is_string($args['info'] ?? null) || ($args['info'] ?? null) instanceof Closure
                ? $args['info']
                : '',
        ))->prompt();
    }

    /**
     * Build and run a SelectPrompt (discard return value).
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
