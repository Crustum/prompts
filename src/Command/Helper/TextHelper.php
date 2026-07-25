<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\TextHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\TextHelper is deprecated. Use Crustum\Prompts\Console\Helper\TextHelper instead.');
}

class_alias(TextHelper::class, 'Crustum\Prompts\Command\Helper\TextHelper');
