<?php
declare(strict_types=1);

namespace Crustum\Prompts\Cake;

use Cake\Console\ConsoleIo;
use Cake\Console\Helper\BannerHelper;
use Cake\Console\Helper\ProgressHelper as CakeProgressHelper;
use Cake\Console\Helper\TableHelper as CakeTableHelper;
use Closure;
use Laravel\Prompts\AutoCompletePrompt;
use Laravel\Prompts\Callout;
use Laravel\Prompts\Clear;
use Laravel\Prompts\ConfirmPrompt;
use Laravel\Prompts\DataTablePrompt;
use Laravel\Prompts\Elements\BulletedList;
use Laravel\Prompts\Elements\ElementContract;
use Laravel\Prompts\Elements\Heading;
use Laravel\Prompts\Elements\KeyValueList;
use Laravel\Prompts\Elements\Link;
use Laravel\Prompts\Elements\NumberedList;
use Laravel\Prompts\Grid;
use Laravel\Prompts\MultiSearchPrompt;
use Laravel\Prompts\MultiSelectPrompt;
use Laravel\Prompts\Note;
use Laravel\Prompts\NotifyPrompt;
use Laravel\Prompts\NumberPrompt;
use Laravel\Prompts\PasswordPrompt;
use Laravel\Prompts\PausePrompt;
use Laravel\Prompts\Progress;
use Laravel\Prompts\Prompt;
use Laravel\Prompts\SearchPrompt;
use Laravel\Prompts\SelectPrompt;
use Laravel\Prompts\Spinner;
use Laravel\Prompts\SuggestPrompt;
use Laravel\Prompts\Support\Logger;
use Laravel\Prompts\Table;
use Laravel\Prompts\Task;
use Laravel\Prompts\TextareaPrompt;
use Laravel\Prompts\TextPrompt;
use Laravel\Prompts\Title;
use ReflectionProperty;
use RuntimeException;
use Throwable;

/**
 * Registers ConsoleIo-backed fallbacks for interactive, display, and progress prompts.
 *
 * Progress, Spinner, and Task fallbacks are invoked from Cake helpers when
 * shouldFallback() is true because upstream map(), spin(), and run() do not
 * delegate to registered fallbacks automatically.
 */
class ConsoleIoFallbacks
{
    /**
     * Shared ConsoleIo used by registered fallbacks.
     *
     * @var \Cake\Console\ConsoleIo|null
     */
    protected static ?ConsoleIo $io = null;

    /**
     * Set the ConsoleIo instance used by fallbacks.
     *
     * Enables Laravel Prompts fallbacks on Windows and when the ConsoleIo is
     * non-interactive, matching upstream's unsupported-environment guidance.
     *
     * @param \Cake\Console\ConsoleIo $io Console IO
     * @return void
     */
    public static function setIo(ConsoleIo $io): void
    {
        static::$io = $io;
        static::enableEnvironmentFallbacks($io);
    }

    /**
     * Enable Laravel Prompts fallbacks for unsupported environments.
     *
     * Always enables on Windows. When a ConsoleIo is provided, also enables when
     * that IO is non-interactive. Uses Prompt::fallbackWhen() which is sticky.
     *
     * @param \Cake\Console\ConsoleIo|null $io Optional ConsoleIo to inspect
     * @return void
     */
    public static function enableEnvironmentFallbacks(?ConsoleIo $io = null): void
    {
        $nonInteractive = false;
        if ($io instanceof ConsoleIo) {
            $property = new ReflectionProperty(ConsoleIo::class, 'interactive');
            $nonInteractive = $property->getValue($io) !== true;
        }

        Prompt::fallbackWhen(PHP_OS_FAMILY === 'Windows' || $nonInteractive);
    }

    /**
     * Whether a ConsoleIo instance has been registered.
     *
     * @return bool
     */
    public static function hasIo(): bool
    {
        return static::$io instanceof ConsoleIo;
    }

    /**
     * Require a ConsoleIo instance to have been set.
     *
     * @return \Cake\Console\ConsoleIo
     * @throws \RuntimeException When no ConsoleIo has been registered
     */
    public static function requireIo(): ConsoleIo
    {
        if (!static::$io instanceof ConsoleIo) {
            throw new RuntimeException(
                'No ConsoleIo registered for prompt fallbacks. Call ConsoleIoFallbacks::setIo() first.',
            );
        }

        return static::$io;
    }

    /**
     * Register default fallbacks for all interactive and display prompts.
     *
     * When `$io` is provided it is stored via setIo(). Fallbacks call requireIo() at runtime
     * so helpers may call setIo() later before prompting.
     *
     * @param \Cake\Console\ConsoleIo|null $io Optional ConsoleIo to bind immediately
     * @return void
     */
    public static function registerDefaults(?ConsoleIo $io = null): void
    {
        if ($io instanceof ConsoleIo) {
            static::setIo($io);
        } else {
            static::enableEnvironmentFallbacks();
        }

        static::registerInputFallbacks();
        static::registerDisplayFallbacks();
        static::registerProgressFallbacks();
    }

    /**
     * Map over progress steps using the Cake Progress helper.
     *
     * @param \Laravel\Prompts\Progress<int|iterable<mixed>> $progress Progress instance
     * @param \Closure(mixed, \Laravel\Prompts\Progress<int|iterable<mixed>>): mixed $callback Step callback
     * @return array<mixed>
     * @throws \Throwable When the callback throws
     */
    public static function mapProgress(Progress $progress, Closure $callback): array
    {
        $io = static::requireIo();
        $progressHelper = new CakeProgressHelper($io);
        $progressHelper->init(['total' => $progress->total]);

        if ($progress->label !== '') {
            $io->out($progress->label);
        }

        $io->out('', 0);

        $result = [];

        try {
            if (is_int($progress->steps)) {
                for ($index = 0; $index < $progress->steps; $index++) {
                    $result[] = $callback($index, $progress);
                    $progressHelper->increment();
                    $progressHelper->draw();
                }
            } else {
                foreach ($progress->steps as $step) {
                    $result[] = $callback($step, $progress);
                    $progressHelper->increment();
                    $progressHelper->draw();
                }
            }
        } catch (Throwable $throwable) {
            $io->out('');

            throw $throwable;
        }

        $io->out('');

        return $result;
    }

    /**
     * Run a spinner callback with a simple ConsoleIo message.
     *
     * @template TReturn
     * @param \Laravel\Prompts\Spinner $spinner Spinner instance
     * @param \Closure(): TReturn $callback Callback to execute
     * @return TReturn
     */
    public static function spin(Spinner $spinner, Closure $callback): mixed
    {
        $io = static::requireIo();

        if ($spinner->message !== '') {
            $io->out($spinner->message);
        }

        return $callback();
    }

    /**
     * Run a task callback with ConsoleIo output and a no-op logger.
     *
     * @template TReturn
     * @param \Laravel\Prompts\Task $task Task instance
     * @param \Closure(\Laravel\Prompts\Support\Logger): TReturn $callback Task callback
     * @return TReturn
     */
    public static function runTask(Task $task, Closure $callback): mixed
    {
        $io = static::requireIo();

        if ($task->label !== '') {
            $io->out($task->label);
        }

        $logger = new Logger($task->identifier);
        $result = $callback($logger);

        if ($task->label !== '') {
            $io->out('<success>✔</success> ' . $task->label);
        }

        return $result;
    }

    /**
     * Register fallbacks for interactive input prompts.
     *
     * @return void
     */
    protected static function registerInputFallbacks(): void
    {
        TextPrompt::fallbackUsing(fn(TextPrompt $prompt): string => static::requireIo()->ask($prompt->label, $prompt->default));

        ConfirmPrompt::fallbackUsing(function (ConfirmPrompt $prompt): bool {
            $default = $prompt->default ? 'y' : 'n';
            $answer = strtolower(static::requireIo()->askChoice($prompt->label, ['y', 'n'], $default));

            return $answer === 'y';
        });

        SelectPrompt::fallbackUsing(fn(SelectPrompt $prompt): int|string|null => static::askSelectKey(
            static::requireIo(),
            $prompt->label,
            $prompt->options,
            $prompt->default,
        ));

        MultiSelectPrompt::fallbackUsing(fn(MultiSelectPrompt $prompt): array => static::askMultiNumbers(
            static::requireIo(),
            $prompt->label,
            $prompt->options,
            $prompt->default,
        ));

        PasswordPrompt::fallbackUsing(fn(PasswordPrompt $prompt): string => static::requireIo()->ask($prompt->label));

        NumberPrompt::fallbackUsing(function (NumberPrompt $prompt): int|string {
            $answer = static::requireIo()->ask($prompt->label, $prompt->default);

            if ($answer === '') {
                return '';
            }

            if (is_numeric($answer) && (string)(int)$answer === $answer) {
                return (int)$answer;
            }

            return $answer;
        });

        TextareaPrompt::fallbackUsing(fn(TextareaPrompt $prompt): string => static::requireIo()->ask($prompt->label, $prompt->default));

        SuggestPrompt::fallbackUsing(function (SuggestPrompt $prompt): string {
            static::printSuggestOptions($prompt);

            return static::requireIo()->ask($prompt->label, $prompt->default);
        });

        AutoCompletePrompt::fallbackUsing(function (AutoCompletePrompt $prompt): string {
            static::printSuggestOptions($prompt);

            return static::requireIo()->ask($prompt->label, $prompt->default);
        });

        PausePrompt::fallbackUsing(function (PausePrompt $prompt): bool {
            static::requireIo()->ask($prompt->message, '');

            return true;
        });

        SearchPrompt::fallbackUsing(function (SearchPrompt $prompt): int|string|null {
            $io = static::requireIo();
            $query = trim($io->ask($prompt->label));

            $options = ($prompt->options)($query);

            if ($options === []) {
                return '';
            }

            return static::askSelectKey($io, 'Select an option', $options);
        });

        MultiSearchPrompt::fallbackUsing(function (MultiSearchPrompt $prompt): array {
            $io = static::requireIo();
            $query = trim($io->ask($prompt->label));
            $options = ($prompt->options)($query);

            if ($options === []) {
                return [];
            }

            return static::askMultiNumbers($io, 'Select options', $options);
        });

        DataTablePrompt::fallbackUsing(fn(DataTablePrompt $prompt): int|string|null => static::askDataTableSelection($prompt));
    }

    /**
     * Register fallbacks for display-only prompts.
     *
     * @return void
     */
    protected static function registerDisplayFallbacks(): void
    {
        Note::fallbackUsing(function (Note $note): bool {
            static::outputNote($note);

            return true;
        });

        Callout::fallbackUsing(function (Callout $callout): bool {
            static::outputCallout($callout);

            return true;
        });

        Table::fallbackUsing(function (Table $table): bool {
            static::outputTable($table);

            return true;
        });

        Grid::fallbackUsing(function (Grid $grid): bool {
            static::outputGrid($grid);

            return true;
        });

        Clear::fallbackUsing(function (): bool {
            $io = static::requireIo();

            for ($line = 0; $line < 3; $line++) {
                $io->out('');
            }

            return true;
        });

        Title::fallbackUsing(function (Title $title): bool {
            if ($title->title !== '') {
                static::requireIo()->out('[title] ' . $title->title);
            }

            return true;
        });

        NotifyPrompt::fallbackUsing(function (NotifyPrompt $prompt): bool {
            $io = static::requireIo();
            $io->out(sprintf(
                '[notify] %s%s',
                $prompt->title,
                $prompt->body !== '' ? ': ' . $prompt->body : '',
            ));

            return true;
        });
    }

    /**
     * Register fallback hooks for progress-style prompts (invoked from Cake helpers).
     *
     * @return void
     */
    protected static function registerProgressFallbacks(): void
    {
        Progress::fallbackUsing(fn(Progress $progress): never => throw new RuntimeException(
            'Progress fallback must be invoked via map(); use ConsoleIoFallbacks::mapProgress().',
        ));

        Spinner::fallbackUsing(fn(Spinner $spinner): never => throw new RuntimeException(
            'Spinner fallback must be invoked via spin(); use ConsoleIoFallbacks::spin().',
        ));

        Task::fallbackUsing(fn(Task $task): never => throw new RuntimeException(
            'Task fallback must be invoked via run(); use ConsoleIoFallbacks::runTask().',
        ));
    }

    /**
     * Ask for a single selection and map the answer back to its option key.
     *
     * @param \Cake\Console\ConsoleIo $io Console IO
     * @param string $label Prompt label
     * @param array<int|string, string> $options Selectable options
     * @param string|int|null $default Default option key
     * @return string|int|null Selected key or value
     */
    protected static function askSelectKey(
        ConsoleIo $io,
        string $label,
        array $options,
        int|string|null $default = null,
    ): int|string|null {
        if ($options === []) {
            return $default;
        }

        if (array_is_list($options)) {
            $defaultLabel = $default !== null ? (string)$default : null;

            return $io->askChoice($label, $options, $defaultLabel);
        }

        $labels = array_values($options);
        $keys = array_keys($options);
        $defaultLabel = $default !== null && isset($options[$default])
            ? $options[$default]
            : null;
        $choice = $io->askChoice($label, $labels, $defaultLabel);
        $index = array_search(strtolower($choice), array_map(strtolower(...), $labels), true);

        if ($index === false) {
            return $default;
        }

        return $keys[$index];
    }

    /**
     * Ask for multiple selections using numbered comma-separated input.
     *
     * @param \Cake\Console\ConsoleIo $io Console IO
     * @param string $label Prompt label
     * @param array<int|string, string> $options Selectable options
     * @param array<int|string> $default Default selected keys
     * @return array<int|string> Selected keys
     */
    protected static function askMultiNumbers(
        ConsoleIo $io,
        string $label,
        array $options,
        array $default = [],
    ): array {
        if ($options === []) {
            return [];
        }

        $isList = array_is_list($options);
        $keys = $isList ? $options : array_keys($options);
        $labels = array_values($options);

        $io->out($label);

        foreach ($labels as $index => $optionLabel) {
            $io->out(sprintf('  %d. %s', $index + 1, $optionLabel));
        }

        $defaultNumbers = [];
        foreach ($default as $defaultValue) {
            $position = array_search($defaultValue, $keys, true);
            if ($position !== false) {
                $defaultNumbers[] = (string)($position + 1);
            }
        }

        $defaultInput = $defaultNumbers !== [] ? implode(',', $defaultNumbers) : '';
        $input = trim($io->ask('Enter numbers separated by commas', $defaultInput));

        if ($input === '') {
            return [];
        }

        if (strtolower($input) === 'all') {
            return $keys;
        }

        $selected = [];
        foreach (preg_split('/\s*,\s*/', $input) ?: [] as $part) {
            if ($part === '') {
                continue;
            }

            if (!ctype_digit($part)) {
                continue;
            }

            $index = (int)$part - 1;
            if (isset($keys[$index])) {
                $selected[] = $keys[$index];
            }
        }

        return array_values(array_unique($selected));
    }

    /**
     * Print suggest/autocomplete options before asking.
     *
     * @param \Laravel\Prompts\SuggestPrompt|\Laravel\Prompts\AutoCompletePrompt $prompt Prompt instance
     * @return void
     */
    protected static function printSuggestOptions(SuggestPrompt|AutoCompletePrompt $prompt): void
    {
        $io = static::requireIo();

        if ($prompt->options instanceof Closure) {
            $options = ($prompt->options)('');
            if (!is_array($options)) {
                $options = is_iterable($options) ? iterator_to_array($options) : [];
            }
        } else {
            $options = $prompt->options;
        }

        if ($options === []) {
            return;
        }

        $io->out('Options:');
        foreach ($options as $option) {
            $io->out('  - ' . $option);
        }
    }

    /**
     * Render a data table and ask for a row selection.
     *
     * @param \Laravel\Prompts\DataTablePrompt $prompt Data table prompt
     * @return string|int|null Selected row key
     */
    protected static function askDataTableSelection(DataTablePrompt $prompt): int|string|null
    {
        $io = static::requireIo();
        $tableRows = [];

        if ($prompt->headers !== []) {
            $tableRows[] = $prompt->headers;
        }

        foreach ($prompt->rows as $row) {
            $tableRows[] = $row;
        }

        if ($tableRows !== []) {
            (new CakeTableHelper($io))->output($tableRows);
        }

        $choices = [];
        foreach ($prompt->rows as $key => $row) {
            $choices[$key] = $row[0] ?? (string)$key;
        }

        if ($choices === []) {
            return null;
        }

        return static::askSelectKey(
            $io,
            $prompt->label !== '' ? $prompt->label : 'Select a row',
            $choices,
        );
    }

    /**
     * Output a note using Banner or styled ConsoleIo output.
     *
     * @param \Laravel\Prompts\Note $note Note instance
     * @return void
     */
    protected static function outputNote(Note $note): void
    {
        $io = static::requireIo();
        $type = $note->type;

        if (in_array($type, ['intro', 'outro'], true)) {
            $banner = $io->helper('Banner');
            if (!$banner instanceof BannerHelper) {
                throw new RuntimeException('Banner helper is not available.');
            }

            $banner->withStyle('success.bg')->output([$note->message]);

            return;
        }

        if ($type === 'alert') {
            $banner = $io->helper('Banner');
            if (!$banner instanceof BannerHelper) {
                throw new RuntimeException('Banner helper is not available.');
            }

            $banner->withStyle('error.bg')->output([$note->message]);

            return;
        }

        if ($type === 'error') {
            $io->err($note->message);

            return;
        }

        if ($type === 'warning') {
            $io->out('<warning>' . $note->message . '</warning>');

            return;
        }

        if ($type === 'info') {
            $io->out('<info>' . $note->message . '</info>');

            return;
        }

        $io->out($note->message);
    }

    /**
     * Output a callout with flattened structured content.
     *
     * @param \Laravel\Prompts\Callout $callout Callout instance
     * @return void
     */
    protected static function outputCallout(Callout $callout): void
    {
        $io = static::requireIo();

        if ($callout->label !== '') {
            $io->out($callout->label);
        }

        foreach (static::flattenCalloutContent($callout->content) as $line) {
            $io->out($line);
        }

        if ($callout->info !== '') {
            $io->out($callout->info);
        }
    }

    /**
     * Flatten callout content into display lines.
     *
     * @param array<int, string|\Laravel\Prompts\Elements\ElementContract>|string $content Callout body
     * @return array<int, string>
     */
    protected static function flattenCalloutContent(string|array $content): array
    {
        if (is_string($content)) {
            return [$content];
        }

        $lines = [];

        foreach ($content as $part) {
            if (is_string($part)) {
                $lines[] = $part;

                continue;
            }

            $lines = [...$lines, ...static::flattenCalloutElement($part)];
        }

        return $lines;
    }

    /**
     * Flatten a single callout element into display lines.
     *
     * @param \Laravel\Prompts\Elements\ElementContract $element Structured content part
     * @return array<int, string>
     */
    protected static function flattenCalloutElement(ElementContract $element): array
    {
        if ($element instanceof BulletedList) {
            $lines = [];
            foreach ($element->items as $item) {
                $lines[] = '  - ' . $item;
                if ($element->spaced) {
                    $lines[] = '';
                }
            }

            return $lines;
        }

        if ($element instanceof NumberedList) {
            $lines = [];
            foreach ($element->items as $index => $item) {
                $lines[] = '  ' . ($index + 1) . '. ' . $item;
                if ($element->spaced) {
                    $lines[] = '';
                }
            }

            return $lines;
        }

        if ($element instanceof KeyValueList) {
            $lines = [];
            foreach ($element->items as $key => $value) {
                $lines[] = '  ' . $key . ': ' . $value;
            }

            return $lines;
        }

        if ($element instanceof Heading) {
            return ['  ' . $element->text];
        }

        if ($element instanceof Link) {
            return ['  ' . $element->label . ' (' . $element->url . ')'];
        }

        return [];
    }

    /**
     * Output a table using Cake's core Table helper.
     *
     * Instantiates Cake\Console\Helper\TableHelper directly to avoid colliding
     * with this plugin's Table helper name.
     *
     * @param \Laravel\Prompts\Table $table Table instance
     * @return void
     */
    public static function outputTable(Table $table): void
    {
        $rows = [];

        if ($table->headers !== []) {
            $rows[] = $table->headers;
        }

        foreach ($table->rows as $row) {
            $rows[] = $row;
        }

        if ($rows === []) {
            return;
        }

        (new CakeTableHelper(static::requireIo()))->output($rows);
    }

    /**
     * Output a grid using Cake's core Table helper without headers.
     *
     * @param \Laravel\Prompts\Grid $grid Grid instance
     * @return void
     */
    public static function outputGrid(Grid $grid): void
    {
        if ($grid->items === []) {
            return;
        }

        $columns = min(3, count($grid->items));
        $rows = array_chunk($grid->items, $columns);

        (new CakeTableHelper(static::requireIo()))
            ->setConfig(['headers' => false])
            ->output($rows);
    }

    /**
     * Output a note using Banner or styled ConsoleIo output.
     *
     * @param \Laravel\Prompts\Note $note Note instance
     * @return void
     */
    public static function renderNote(Note $note): void
    {
        static::outputNote($note);
    }

    /**
     * Clear via ConsoleIo newlines when TTY clear is unavailable.
     *
     * @return void
     */
    public static function renderClear(): void
    {
        $io = static::requireIo();

        for ($line = 0; $line < 3; $line++) {
            $io->out('');
        }
    }

    /**
     * Render a title fallback line via ConsoleIo.
     *
     * @param \Laravel\Prompts\Title $title Title instance
     * @return void
     */
    public static function renderTitle(Title $title): void
    {
        if ($title->title !== '') {
            static::requireIo()->out('[title] ' . $title->title);
        }
    }
}
