<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\SpinHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\SpinHelper is deprecated. Use Crustum\Prompts\Console\Helper\SpinHelper instead.');
}

class_alias(SpinHelper::class, 'Crustum\Prompts\Command\Helper\SpinHelper');
