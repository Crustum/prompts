<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use InvalidArgumentException;
use Laravel\Prompts\MultiSearchPrompt;
use Override;

/**
 * Cake Console helper for MultiSearchPrompt.
 *
 * Expected `$args` keys:
 * - `label` (string, required) — prompt label
 * - `options` (\Closure, required) — search callback
 * - `placeholder` (string) — placeholder when empty
 * - `scroll` (int) — visible option count
 * - `required` (bool|string) — required flag or message
 * - `validate` (mixed) — validator callback or rules
 * - `hint` (string) — hint text
 * - `transform` (\Closure|null) — value transform
 * - `info` (string|\Closure) — optional info text
 */
class MultiSearchHelper extends Helper
{
    /**
     * Build and run a MultiSearchPrompt.
     *
     * @param array<string, mixed> $args Prompt configuration (see class docblock)
     * @return mixed Prompt result
     * @throws \InvalidArgumentException When options is not a Closure
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        $options = $args['options'] ?? null;
        if (!$options instanceof Closure) {
            throw new InvalidArgumentException('MultiSearchHelper requires a Closure in args[options].');
        }

        return (new MultiSearchPrompt(
            label: (string)($args['label'] ?? ''),
            options: $options,
            placeholder: (string)($args['placeholder'] ?? ''),
            scroll: (int)($args['scroll'] ?? 5),
            required: $args['required'] ?? false,
            validate: $args['validate'] ?? null,
            hint: (string)($args['hint'] ?? 'Use the space bar to select options.'),
            transform: ($args['transform'] ?? null) instanceof Closure ? $args['transform'] : null,
            info: is_string($args['info'] ?? null) || ($args['info'] ?? null) instanceof Closure
                ? $args['info']
                : '',
        ))->prompt();
    }

    /**
     * Build and run a MultiSearchPrompt (discard return value).
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
