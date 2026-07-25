<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\NumberHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\NumberHelper is deprecated. Use Crustum\Prompts\Console\Helper\NumberHelper instead.');
}

class_alias(NumberHelper::class, 'Crustum\Prompts\Command\Helper\NumberHelper');