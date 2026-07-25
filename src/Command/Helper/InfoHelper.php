<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\InfoHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\InfoHelper is deprecated. Use Crustum\Prompts\Console\Helper\InfoHelper instead.');
}

class_alias(InfoHelper::class, 'Crustum\Prompts\Command\Helper\InfoHelper');