<?php
declare(strict_types=1);

use Crustum\Prompts\Cake\ConsoleIoFallbacks;
use Crustum\Prompts\Testing\PromptState;
use Laravel\Prompts\ConfirmPrompt;
use Laravel\Prompts\FormBuilder;
use Laravel\Prompts\Progress;
use Laravel\Prompts\Prompt;
use Laravel\Prompts\TextPrompt;

beforeEach(function (): void {
    Prompt::fallbackWhen(true);
});

it('enables fallbacks on Windows during registerDefaults', function (): void {
    PromptState::clearFallbackFlag();
    ConsoleIoFallbacks::registerDefaults();

    if (PHP_OS_FAMILY === 'Windows') {
        expect(ConfirmPrompt::shouldFallback())->toBeTrue()
            ->and(TextPrompt::shouldFallback())->toBeTrue();

        return;
    }

    expect(ConfirmPrompt::shouldFallback())->toBeFalse();

    Prompt::fallbackWhen(true);

    expect(ConfirmPrompt::shouldFallback())->toBeTrue();
});

it('enables fallbacks when ConsoleIo is non-interactive', function (): void {
    PromptState::clearFallbackFlag();

    $io = promptsIo();
    $io->setInteractive(false);

    ConsoleIoFallbacks::setIo($io);

    expect(ConfirmPrompt::shouldFallback())->toBeTrue();
});

it('runs Confirm through Cake askChoice fallback', function (): void {
    $result = promptsIo(['y'])->helper(promptsHelper('Confirm'))->run([
        'label' => 'Continue?',
    ]);

    expect($result)->toBeTrue();
});

it('runs Confirm no through Cake askChoice fallback', function (): void {
    $result = promptsIo(['n'])->helper(promptsHelper('Confirm'))->run([
        'label' => 'Continue?',
        'default' => true,
    ]);

    expect($result)->toBeFalse();
});

it('runs Text through Cake ask fallback', function (): void {
    $result = promptsIo(['Ada'])->helper(promptsHelper('Text'))->run([
        'label' => 'Name',
        'default' => '',
    ]);

    expect($result)->toBe('Ada');
});

it('runs Password through Cake ask fallback', function (): void {
    $result = promptsIo(['secret'])->helper(promptsHelper('Password'))->run([
        'label' => 'Password',
    ]);

    expect($result)->toBe('secret');
});

it('runs Number through Cake ask fallback', function (): void {
    $result = promptsIo(['42'])->helper(promptsHelper('Number'))->run([
        'label' => 'Age',
    ]);

    expect($result)->toBe(42);
});

it('runs Select through Cake askChoice fallback', function (): void {
    $result = promptsIo(['Green'])->helper(promptsHelper('Select'))->run([
        'label' => 'Color',
        'options' => ['Red', 'Green', 'Blue'],
    ]);

    expect($result)->toBe('Green');
});

it('runs MultiSelect through numbered Cake input fallback', function (): void {
    $result = promptsIo(['1,3'])->helper(promptsHelper('MultiSelect'))->run([
        'label' => 'Colors',
        'options' => ['Red', 'Green', 'Blue'],
    ]);

    expect($result)->toBe(['Red', 'Blue']);
});

it('runs Pause through Cake ask fallback', function (): void {
    $result = promptsIo([''])->helper(promptsHelper('Pause'))->run([
        'message' => 'Press enter',
    ]);

    expect($result)->toBeTrue();
});

it('renders Note through Cake ConsoleIo fallback', function (): void {
    [$io, $stdout] = promptsIoWithStdout();

    $io->helper(promptsHelper('Note'))->run([
        'message' => 'Fallback note',
        'type' => 'info',
    ]);

    expect($stdout->output())->toContain('Fallback note');
});

it('renders Info helper through Cake ConsoleIo fallback', function (): void {
    [$io, $stdout] = promptsIoWithStdout();

    $io->helper(promptsHelper('Info'))->run([
        'message' => 'Info message',
    ]);

    expect($stdout->output())->toContain('Info message');
});

it('renders Table through Cake Table helper fallback', function (): void {
    [$io, $stdout] = promptsIoWithStdout();

    $io->helper(promptsHelper('Table'))->run([
        'headers' => ['Name', 'Role'],
        'rows' => [
            ['Ada', 'Engineer'],
        ],
    ]);

    expect($stdout->output())->toContain('Ada')
        ->and($stdout->output())->toContain('Engineer');
});

it('runs Spin through ConsoleIoFallbacks', function (): void {
    $result = promptsIo()->helper(promptsHelper('Spin'))->run([
        'message' => 'Working',
        'callback' => fn (): int => 7,
    ]);

    expect($result)->toBe(7)
        ->and(ConsoleIoFallbacks::hasIo())->toBeTrue();
});

it('maps Progress through Cake Progress helper fallback', function (): void {
    $result = promptsIo()->helper(promptsHelper('Progress'))->run([
        'label' => 'Steps',
        'steps' => 3,
        'callback' => fn (int $step): int => $step * 2,
    ]);

    expect($result)->toBe([0, 2, 4]);
});

it('returns a FormBuilder from the Form helper', function (): void {
    $builder = promptsIo()->helper(promptsHelper('Form'))->run([]);

    expect($builder)->toBeInstanceOf(FormBuilder::class);
});

it('returns a Progress instance when Progress has no callback', function (): void {
    $progress = promptsIo()->helper(promptsHelper('Progress'))->run([
        'label' => 'Working',
        'steps' => 3,
    ]);

    expect($progress)->toBeInstanceOf(Progress::class)
        ->and($progress->total)->toBe(3);
});

it('binds ConsoleIo on the helper before prompting', function (): void {
    $io = promptsIo(['ok']);
    $io->helper(promptsHelper('Text'))->run([
        'label' => 'Value',
    ]);

    expect(ConsoleIoFallbacks::hasIo())->toBeTrue()
        ->and(ConsoleIoFallbacks::requireIo())->toBe($io);
});
