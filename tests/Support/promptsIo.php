<?php
declare(strict_types=1);

use Cake\Console\ConsoleIo;
use Cake\Console\TestSuite\StubConsoleInput;
use Cake\Console\TestSuite\StubConsoleOutput;

/**
 * Resolve a Prompts plugin helper name for ConsoleIo.
 *
 * @param string $helper Helper short name (Confirm, Text, etc.)
 * @return string Fully qualified helper name
 */
function promptsHelper(string $helper): string
{
    return 'Crustum/Prompts.' . $helper;
}

/**
 * Create a ConsoleIo instance for prompt helper tests.
 *
 * @param array<int|string, string> $input Stub stdin input lines
 * @return \Cake\Console\ConsoleIo
 */
function promptsIo(array $input = []): ConsoleIo
{
    return new ConsoleIo(
        new StubConsoleOutput(),
        new StubConsoleOutput(),
        new StubConsoleInput($input),
    );
}

/**
 * Create ConsoleIo with capturable stdout for display assertions.
 *
 * @param array<int|string, string> $input Stub stdin input lines
 * @return array{0: \Cake\Console\ConsoleIo, 1: \Cake\Console\TestSuite\StubConsoleOutput}
 */
function promptsIoWithStdout(array $input = []): array
{
    $stdout = new StubConsoleOutput();
    $io = new ConsoleIo(
        $stdout,
        new StubConsoleOutput(),
        new StubConsoleInput($input),
    );

    return [$io, $stdout];
}
