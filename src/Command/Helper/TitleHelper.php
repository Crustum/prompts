<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\TitleHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\TitleHelper is deprecated. Use Crustum\Prompts\Console\Helper\TitleHelper instead.');
}

class_alias(TitleHelper::class, 'Crustum\Prompts\Command\Helper\TitleHelper');
