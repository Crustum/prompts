<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use InvalidArgumentException;
use Laravel\Prompts\Progress;
use Override;

/**
 * Cake Console helper for Progress.
 *
 * Expected `$args` keys:
 * - `label` (string, required) — progress label
 * - `steps` (iterable|int, required) — step count or iterable of steps
 * - `callback` (\Closure|null) — when set, maps over steps and returns results
 * - `hint` (string) — optional hint shown while active
 */
class ProgressHelper extends Helper
{
    /**
     * Create or run a progress bar.
     *
     * @param array<string, mixed> $args Progress configuration (see class docblock)
     * @return mixed Progress instance or mapped results
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        $steps = $args['steps'] ?? 0;
        if (!is_iterable($steps) && !is_int($steps)) {
            $steps = 0;
        }

        $progress = new Progress(
            label: (string)($args['label'] ?? ''),
            steps: $steps,
            hint: (string)($args['hint'] ?? ''),
        );

        $callback = $args['callback'] ?? null;
        if ($callback instanceof Closure) {
            $mapCallback = $this->requireProgressCallback($args);

            if (Progress::shouldFallback() && ConsoleIoFallbacks::hasIo()) {
                return ConsoleIoFallbacks::mapProgress($progress, $mapCallback);
            }

            return $progress->map($mapCallback);
        }

        return $progress;
    }

    /**
     * Require a progress map callback from helper arguments.
     *
     * @param array<string, mixed> $args Helper arguments
     * @return \Closure(mixed, \Laravel\Prompts\Progress<int|iterable<mixed>>): mixed
     * @throws \InvalidArgumentException When callback is not a Closure
     */
    private function requireProgressCallback(array $args): Closure
    {
        $callback = $args['callback'] ?? null;
        if (!$callback instanceof Closure) {
            throw new InvalidArgumentException('The "callback" argument must be a Closure.');
        }

        return $callback;
    }

    /**
     * Create or run a progress bar.
     *
     * @param array<string, mixed> $args Progress configuration (see class docblock)
     * @return void
     */
    #[Override]
    public function output(array $args): void
    {
        $this->run($args);
    }
}
