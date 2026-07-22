<?php
declare(strict_types=1);

use Crustum\Prompts\Testing\PromptState;
use Laravel\Prompts\FormBuilder;
use Laravel\Prompts\Key;
use Laravel\Prompts\Prompt;

beforeEach(function (): void {
    if (PHP_OS_FAMILY === 'Windows') {
        $this->markTestSkipped('Laravel Prompts interactive TTY requires Linux/WSL');
    }

    PromptState::clearFallbackFlag();
});

it('runs Confirm through the helper with faked keys', function (): void {
    Prompt::fake([Key::ENTER]);

    $result = promptsIo()->helper(promptsHelper('Confirm'))->run([
        'label' => 'Are you sure?',
    ]);

    expect($result)->toBeTrue();
});

it('runs Text through the helper with faked keys', function (): void {
    Prompt::fake(['Jess', Key::ENTER]);

    $result = promptsIo()->helper(promptsHelper('Text'))->run([
        'label' => 'What is your name?',
    ]);

    expect($result)->toBe('Jess');
});

it('runs Select through the helper with faked keys', function (): void {
    Prompt::fake([Key::DOWN, Key::ENTER]);

    $result = promptsIo()->helper(promptsHelper('Select'))->run([
        'label' => 'Pick a color',
        'options' => ['Red', 'Green', 'Blue'],
    ]);

    expect($result)->toBe('Green');
});

it('runs MultiSelect through the helper with faked keys', function (): void {
    Prompt::fake([Key::SPACE, Key::DOWN, Key::SPACE, Key::ENTER]);

    $result = promptsIo()->helper(promptsHelper('MultiSelect'))->run([
        'label' => 'Pick colors',
        'options' => ['Red', 'Green', 'Blue'],
    ]);

    expect($result)->toBe(['Red', 'Green']);
});

it('runs Password through the helper with faked keys', function (): void {
    Prompt::fake(['secret', Key::ENTER]);

    $result = promptsIo()->helper(promptsHelper('Password'))->run([
        'label' => 'Password',
    ]);

    expect($result)->toBe('secret');
});

it('runs Number through the helper with faked keys', function (): void {
    Prompt::fake(['42', Key::ENTER]);

    $result = promptsIo()->helper(promptsHelper('Number'))->run([
        'label' => 'Age',
    ]);

    expect($result)->toBe(42);
});

it('runs Pause through the helper with faked keys', function (): void {
    Prompt::fake([Key::ENTER]);

    $result = promptsIo()->helper(promptsHelper('Pause'))->run([
        'message' => 'Press enter',
    ]);

    expect($result)->toBeTrue();
});

it('displays Note through the helper with faked output', function (): void {
    Prompt::fake();

    promptsIo()->helper(promptsHelper('Note'))->run([
        'message' => 'Hello from note',
        'type' => 'info',
    ]);

    Prompt::assertOutputContains('Hello from note');
});

it('displays Table through the helper with faked output', function (): void {
    Prompt::fake();

    promptsIo()->helper(promptsHelper('Table'))->run([
        'headers' => ['Name', 'Role'],
        'rows' => [
            ['Ada', 'Engineer'],
        ],
    ]);

    Prompt::assertOutputContains('Ada');
    Prompt::assertOutputContains('Engineer');
});

it('maps Progress through the helper with faked output', function (): void {
    Prompt::fake();

    $result = promptsIo()->helper(promptsHelper('Progress'))->run([
        'label' => 'Working',
        'steps' => 2,
        'callback' => fn (int $step): int => $step + 1,
    ]);

    expect($result)->toBe([1, 2]);
});

it('runs Spin through the helper', function (): void {
    Prompt::fake();

    $result = promptsIo()->helper(promptsHelper('Spin'))->run([
        'message' => 'Loading',
        'callback' => fn (): string => 'done',
    ]);

    expect($result)->toBe('done');
});

it('returns a FormBuilder from the Form helper', function (): void {
    $builder = promptsIo()->helper(promptsHelper('Form'))->run([]);

    expect($builder)->toBeInstanceOf(FormBuilder::class);
});
