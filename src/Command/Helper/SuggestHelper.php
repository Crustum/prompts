<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Collection\Collection;
use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\SuggestPrompt;
use Override;

/**
 * Cake Console helper for SuggestPrompt.
 *
 * Expected `$args` keys:
 * - `label` (string, required) — prompt label
 * - `options` (array|\Cake\Collection\Collection|\Closure, required) — suggestion options
 * - `placeholder` (string) — placeholder when empty
 * - `default` (string) — initial value
 * - `scroll` (int) — visible option count
 * - `required` (bool|string) — required flag or message
 * - `validate` (mixed) — validator callback or rules
 * - `hint` (string) — hint text
 * - `transform` (\Closure|null) — value transform
 * - `info` (string|\Closure) — optional info text
 */
class SuggestHelper extends Helper
{
    /**
     * Build and run a SuggestPrompt.
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

        return (new SuggestPrompt(
            label: (string)($args['label'] ?? ''),
            options: $options,
            placeholder: (string)($args['placeholder'] ?? ''),
            default: (string)($args['default'] ?? ''),
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
     * Build and run a SuggestPrompt (discard return value).
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
