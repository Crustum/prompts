<?php
declare(strict_types=1);

use Crustum\Prompts\Console\Helper\AlertHelper;
use Crustum\Prompts\Console\Helper\AutoCompleteHelper;
use Crustum\Prompts\Console\Helper\CalloutHelper;
use Crustum\Prompts\Console\Helper\ClearHelper;
use Crustum\Prompts\Console\Helper\ConfirmHelper;
use Crustum\Prompts\Console\Helper\DataTableHelper;
use Crustum\Prompts\Console\Helper\ErrorHelper;
use Crustum\Prompts\Console\Helper\FormHelper;
use Crustum\Prompts\Console\Helper\GridHelper;
use Crustum\Prompts\Console\Helper\InfoHelper;
use Crustum\Prompts\Console\Helper\IntroHelper;
use Crustum\Prompts\Console\Helper\MultiSearchHelper;
use Crustum\Prompts\Console\Helper\MultiSelectHelper;
use Crustum\Prompts\Console\Helper\NoteHelper;
use Crustum\Prompts\Console\Helper\NotifyHelper;
use Crustum\Prompts\Console\Helper\NumberHelper;
use Crustum\Prompts\Console\Helper\OutroHelper;
use Crustum\Prompts\Console\Helper\PasswordHelper;
use Crustum\Prompts\Console\Helper\PauseHelper;
use Crustum\Prompts\Console\Helper\ProgressHelper;
use Crustum\Prompts\Console\Helper\SearchHelper;
use Crustum\Prompts\Console\Helper\SelectHelper;
use Crustum\Prompts\Console\Helper\SpinHelper;
use Crustum\Prompts\Console\Helper\StreamHelper;
use Crustum\Prompts\Console\Helper\SuggestHelper;
use Crustum\Prompts\Console\Helper\TableHelper;
use Crustum\Prompts\Console\Helper\TaskHelper;
use Crustum\Prompts\Console\Helper\TextareaHelper;
use Crustum\Prompts\Console\Helper\TextHelper;
use Crustum\Prompts\Console\Helper\TitleHelper;
use Crustum\Prompts\Console\Helper\WarningHelper;

it('resolves every Console helper from the Prompts plugin', function (string $name, string $class): void {
    $helper = promptsIo()->helper(promptsHelper($name));

    expect($helper)->toBeInstanceOf($class);
})->with([
    ['Alert', AlertHelper::class],
    ['AutoComplete', AutoCompleteHelper::class],
    ['Callout', CalloutHelper::class],
    ['Clear', ClearHelper::class],
    ['Confirm', ConfirmHelper::class],
    ['DataTable', DataTableHelper::class],
    ['Error', ErrorHelper::class],
    ['Form', FormHelper::class],
    ['Grid', GridHelper::class],
    ['Info', InfoHelper::class],
    ['Intro', IntroHelper::class],
    ['MultiSearch', MultiSearchHelper::class],
    ['MultiSelect', MultiSelectHelper::class],
    ['Note', NoteHelper::class],
    ['Notify', NotifyHelper::class],
    ['Number', NumberHelper::class],
    ['Outro', OutroHelper::class],
    ['Password', PasswordHelper::class],
    ['Pause', PauseHelper::class],
    ['Progress', ProgressHelper::class],
    ['Search', SearchHelper::class],
    ['Select', SelectHelper::class],
    ['Spin', SpinHelper::class],
    ['Stream', StreamHelper::class],
    ['Suggest', SuggestHelper::class],
    ['Table', TableHelper::class],
    ['Task', TaskHelper::class],
    ['Text', TextHelper::class],
    ['Textarea', TextareaHelper::class],
    ['Title', TitleHelper::class],
    ['Warning', WarningHelper::class],
]);
