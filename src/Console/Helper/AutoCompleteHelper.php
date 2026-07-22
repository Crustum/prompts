<?php
declare(strict_types=1);

namespace Crustum\Prompts\Console\Helper;

use Cake\Collection\Collection;
use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\AutoCompletePrompt;
use Override;

/**
 * Cake Console helper for AutoCompletePrompt.
 *
 * Expected `$args` keys:
 * - `label` (string, required) — prompt label
 * - `options` (array|\Cake\Collection\Collection|\Closure) — autocomplete options
 * - `placeholder` (string) — placeholder when empty
 * - `default` (string) — initial value
 * - `required` (bool|string) — required flag or message
 * - `validate` (mixed) — validator callback or rules
 * - `hint` (string) — hint text
 * - `transform` (\Closure|null) — value transform
 */
class AutoCompleteHelper extends Helper
{
    /**
     * Build and run an AutoCompletePrompt.
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

        if (
            !$options instanceof Closure
            && !is_array($options)
        ) {
            $options = [];
        }

        return (new AutoCompletePrompt(
            label: (string)($args['label'] ?? ''),
            options: $options,
            placeholder: (string)($args['placeholder'] ?? ''),
            default: (string)($args['default'] ?? ''),
            required: $args['required'] ?? false,
            validate: $args['validate'] ?? null,
            hint: (string)($args['hint'] ?? ''),
            transform: ($args['transform'] ?? null) instanceof Closure ? $args['transform'] : null,
        ))->prompt();
    }

    /**
     * Build and run an AutoCompletePrompt (discard return value).
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
