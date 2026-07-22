<?php
declare(strict_types=1);

namespace Crustum\Prompts\Console\Helper;

use Cake\Console\Helper;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Laravel\Prompts\FormBuilder;
use Override;

/**
 * Cake Console helper that returns a FormBuilder instance.
 */
class FormHelper extends Helper
{
    /**
     * Create a FormBuilder.
     *
     * @param array<string, mixed> $args Unused
     * @return \Laravel\Prompts\FormBuilder Form builder
     */
    public function run(array $args = []): mixed
    {
        ConsoleIoFallbacks::setIo($this->_io);

        return new FormBuilder();
    }

    /**
     * Create a FormBuilder (discard return value).
     *
     * Prefer {@see run()} to obtain the builder.
     *
     * @param array<string, mixed> $args Unused
     * @return void
     */
    #[Override]
    public function output(array $args): void
    {
        $this->run($args);
    }
}
