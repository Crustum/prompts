<?php
declare(strict_types=1);

namespace Crustum\Prompts\Console\Helper;

use Cake\Collection\Collection;
use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\MultiSelectPrompt;
use Override;

/**
 * Cake Console helper for MultiSelectPrompt.
 *
 * Expected `$args` keys:
 * - `label` (string, required) — prompt label
 * - `options` (array|\Cake\Collection\Collection, required) — selectable options
 * - `default` (array|\Cake\Collection\Collection) — initially selected values
 * - `scroll` (int) — visible option count
 * - `required` (bool|string) — required flag or message
 * - `validate` (mixed) — validator callback or rules
 * - `hint` (string) — hint text
 * - `transform` (\Closure|null) — value transform
 * - `info` (string|\Closure) — optional info text
 */
class MultiSelectHelper extends Helper
{
    /**
     * Build and run a MultiSelectPrompt.
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

        $default = $args['default'] ?? [];
        if ($default instanceof Collection) {
            $default = $default->toArray();
        }

        if (!is_array($default)) {
            $default = [];
        }

        return (new MultiSelectPrompt(
            label: (string)($args['label'] ?? ''),
            options: $options,
            default: $default,
            scroll: (int)($args['scroll'] ?? 5),
            required: $args['required'] ?? false,
            validate: $args['validate'] ?? null,
            hint: (string)($args['hint'] ?? ''),
            transform: ($args['transform'] ?? null) instanceof Closure ? $args['transform'] : null,
            info: is_string($args['info'] ?? null) || ($args['info'] ?? null) instanceof Closure
                ? $args['info']
                : '',
        ))->prompt();
    }

    /**
     * Build and run a MultiSelectPrompt (discard return value).
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
