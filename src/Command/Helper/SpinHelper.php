<?php
declare(strict_types=1);

namespace Crustum\Prompts\Command\Helper;

use Cake\Console\Helper;
use Closure;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use InvalidArgumentException;
use Laravel\Prompts\Spinner;
use Override;

/**
 * Cake Console helper for Spinner.
 *
 * Expected `$args` keys:
 * - `callback` (\Closure, required) — work to run while spinning
 * - `message` (string) — spinner message
 */
class SpinHelper extends Helper
{
    /**
     * Run a callback with a spinner.
     *
     * @param array<string, mixed> $args Spinner configuration (see class docblock)
     * @return mixed Callback result
     * @throws \InvalidArgumentException When callback is not a Closure
     */
    public function run(array $args): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        $callback = $this->requireNullaryCallback($args);

        return $this->spinWithCallback(
            message: (string)($args['message'] ?? ''),
            callback: $callback,
        );
    }

    /**
     * Require a nullary callback from helper arguments.
     *
     * @param array<string, mixed> $args Helper arguments
     * @return \Closure(): mixed
     * @throws \InvalidArgumentException When callback is not a Closure
     */
    private function requireNullaryCallback(array $args): Closure
    {
        $callback = $args['callback'] ?? null;
        if (!$callback instanceof Closure) {
            throw new InvalidArgumentException('The "callback" argument must be a Closure.');
        }

        return $callback;
    }

    /**
     * Render a spinner around a nullary callback.
     *
     * @param \Closure(): mixed $callback Callback executed while the spinner runs
     * @return mixed The callback return value
     */
    private function spinWithCallback(string $message, Closure $callback): mixed
    {
        $spinner = new Spinner($message);

        if (Spinner::shouldFallback() && ConsoleIoFallbacks::hasIo()) {
            return ConsoleIoFallbacks::spin($spinner, $callback);
        }

        return $spinner->spin($callback);
    }

    /**
     * Run a callback with a spinner (discard return value).
     *
     * @param array<string, mixed> $args Spinner configuration (see class docblock)
     * @return void
     */
    #[Override]
    public function output(array $args): void
    {
        $this->run($args);
    }
}
