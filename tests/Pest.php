<?php
declare(strict_types=1);

use Crustum\Prompts\Testing\PromptState;

require __DIR__ . '/Support/promptsIo.php';

expect()->extend('toBeOne', fn () => $this->toBe(1));

uses()
    ->afterEach(function (): void {
        PromptState::reset();
    })
    ->in('Feature');
