<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\ClearHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\ClearHelper is deprecated. Use Crustum\Prompts\Console\Helper\ClearHelper instead.');
}

class_alias(ClearHelper::class, 'Crustum\Prompts\Command\Helper\ClearHelper');