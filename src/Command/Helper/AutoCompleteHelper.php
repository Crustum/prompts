<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\AutoCompleteHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\AutoCompleteHelper is deprecated. Use Crustum\Prompts\Console\Helper\AutoCompleteHelper instead.');
}

class_alias(AutoCompleteHelper::class, 'Crustum\Prompts\Command\Helper\AutoCompleteHelper');
