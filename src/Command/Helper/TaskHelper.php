<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\TaskHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\TaskHelper is deprecated. Use Crustum\Prompts\Console\Helper\TaskHelper instead.');
}

class_alias(TaskHelper::class, 'Crustum\Prompts\Command\Helper\TaskHelper');