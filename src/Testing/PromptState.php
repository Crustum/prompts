<?php
declare(strict_types=1);

namespace Crustum\Prompts\Testing;

use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\Output\ConsoleOutput;
use Laravel\Prompts\Prompt;
use ReflectionClass;

/**
 * Resets Laravel Prompts static state between tests.
 *
 * Upstream exposes fallbackWhen() but no clearFallback(). After clearing the
 * sticky flag, re-applies Windows / environment fallbacks via ConsoleIoFallbacks.
 */
class PromptState
{
    /**
     * Clear the sticky shouldFallback flag without re-applying environment defaults.
     *
     * @return void
     */
    public static function clearFallbackFlag(): void
    {
        static::setFallbackFlag(Prompt::class, false);
    }

    /**
     * Reset fallback flag and default output, then re-enable environment fallbacks.
     *
     * @return void
     */
    public static function reset(): void
    {
        static::clearFallbackFlag();
        Prompt::setOutput(new ConsoleOutput());
        ConsoleIoFallbacks::enableEnvironmentFallbacks();
    }

    /**
     * Set the shouldFallback flag on a prompt class via reflection.
     *
     * @param class-string $class Prompt class name
     * @param bool $value Fallback flag value
     * @return void
     */
    protected static function setFallbackFlag(string $class, bool $value): void
    {
        $reflection = new ReflectionClass($class);

        while (true) {
            if ($reflection->hasProperty('shouldFallback')) {
                $property = $reflection->getProperty('shouldFallback');
                $property->setValue(null, $value);

                return;
            }

            $parent = $reflection->getParentClass();
            if ($parent === false) {
                return;
            }

            $reflection = $parent;
        }
    }
}
