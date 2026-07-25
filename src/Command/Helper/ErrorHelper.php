<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\ErrorHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\ErrorHelper is deprecated. Use Crustum\Prompts\Console\Helper\ErrorHelper instead.');
}

class_alias(ErrorHelper::class, 'Crustum\Prompts\Command\Helper\ErrorHelper');
