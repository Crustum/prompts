<?php
declare(strict_types=1);

namespace Crustum\Prompts\Console\Helper;

use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use InvalidArgumentException;
use Laravel\Prompts\Task;
use Override;

/**
 * Cake Console helper for Task.
 *
 * Expected `$args` keys:
 * - `label` (string) — task label
 * - `callback` (\Closure, required) — callback executed while the task runs
 * - `limit` (int|null) — maximum visible log lines
 * - `keepSummary` (bool) — whether to keep the summary after completion
 * - `subLabel` (string|null) — optional sub-label under the main label
 */
class TaskHelper extends Helper
{
    /**
     * Render a task and execute the callback.
     *
     * @param array<string, mixed> $args Task configuration (see class docblock)
     * @return mixed The callback return value
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        $subLabel = $args['subLabel'] ?? null;

        $task = new Task(
            label: (string)($args['label'] ?? ''),
            limit: (int)($args['limit'] ?? 10),
            keepSummary: (bool)($args['keepSummary'] ?? false),
            subLabel: is_string($subLabel) ? $subLabel : null,
        );

        $callback = $this->requireLoggerCallback($args);

        if (Task::shouldFallback() && ConsoleIoFallbacks::hasIo()) {
            return ConsoleIoFallbacks::runTask($task, $callback);
        }

        return $task->run($callback);
    }

    /**
     * Require a logger callback from helper arguments.
     *
     * @param array<string, mixed> $args Helper arguments
     * @return \Closure(\Laravel\Prompts\Support\Logger): mixed
     * @throws \InvalidArgumentException When callback is not a Closure
     */
    private function requireLoggerCallback(array $args): Closure
    {
        $callback = $args['callback'] ?? null;
        if (!$callback instanceof Closure) {
            throw new InvalidArgumentException('The "callback" argument must be a Closure.');
        }

        return $callback;
    }

    /**
     * Render a task and execute the callback.
     *
     * @param array<string, mixed> $args Task configuration (see class docblock)
     * @return void
     */
    #[Override]
    public function output(array $args): void
    {
        $this->run($args);
    }
}
