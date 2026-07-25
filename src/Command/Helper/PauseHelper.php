<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\PauseHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\PauseHelper is deprecated. Use Crustum\Prompts\Console\Helper\PauseHelper instead.');
}

class_alias(PauseHelper::class, 'Crustum\Prompts\Command\Helper\PauseHelper');
