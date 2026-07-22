<?php
declare(strict_types=1);

namespace Crustum\Prompts;

use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Override;

/**
 * CakePHP Prompts plugin — interactive TTY CLI prompts with ConsoleIo fallbacks.
 */
class PromptsPlugin extends BasePlugin
{
    /**
     * Plugin name.
     *
     * @var string|null
     */
    protected ?string $name = 'Prompts';

    /**
     * Console commands are not required for the prompts library itself.
     *
     * @var bool
     */
    protected bool $consoleEnabled = false;

    /**
     * Bootstrap hook for the host application.
     *
     * Registers default ConsoleIo fallbacks for core prompts. Call
     * ConsoleIoFallbacks::setIo() from commands/helpers before prompting when
     * fallbacks may run.
     *
     * @param \Cake\Core\PluginApplicationInterface $app Host application
     * @return void
     */
    #[Override]
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        ConsoleIoFallbacks::registerDefaults();
    }
}
