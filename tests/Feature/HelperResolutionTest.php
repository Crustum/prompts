<?php
declare(strict_types=1);

use Crustum\Prompts\Command\Helper\AlertHelper;
use Crustum\Prompts\Command\Helper\AutoCompleteHelper;
use Crustum\Prompts\Command\Helper\CalloutHelper;
use Crustum\Prompts\Command\Helper\ClearHelper;
use Crustum\Prompts\Command\Helper\ConfirmHelper;
use Crustum\Prompts\Command\Helper\DataTableHelper;
use Crustum\Prompts\Command\Helper\ErrorHelper;
use Crustum\Prompts\Command\Helper\FormHelper;
use Crustum\Prompts\Command\Helper\GridHelper;
use Crustum\Prompts\Command\Helper\InfoHelper;
use Crustum\Prompts\Command\Helper\IntroHelper;
use Crustum\Prompts\Command\Helper\MultiSearchHelper;
use Crustum\Prompts\Command\Helper\MultiSelectHelper;
use Crustum\Prompts\Command\Helper\NoteHelper;
use Crustum\Prompts\Command\Helper\NotifyHelper;
use Crustum\Prompts\Command\Helper\NumberHelper;
use Crustum\Prompts\Command\Helper\OutroHelper;
use Crustum\Prompts\Command\Helper\PasswordHelper;
use Crustum\Prompts\Command\Helper\PauseHelper;
use Crustum\Prompts\Command\Helper\ProgressHelper;
use Crustum\Prompts\Command\Helper\SearchHelper;
use Crustum\Prompts\Command\Helper\SelectHelper;
use Crustum\Prompts\Command\Helper\SpinHelper;
use Crustum\Prompts\Command\Helper\StreamHelper;
use Crustum\Prompts\Command\Helper\SuggestHelper;
use Crustum\Prompts\Command\Helper\TableHelper;
use Crustum\Prompts\Command\Helper\TaskHelper;
use Crustum\Prompts\Command\Helper\TextareaHelper;
use Crustum\Prompts\Command\Helper\TextHelper;
use Crustum\Prompts\Command\Helper\TitleHelper;
use Crustum\Prompts\Command\Helper\WarningHelper;

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
