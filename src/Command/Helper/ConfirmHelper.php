<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\ConfirmHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\ConfirmHelper is deprecated. Use Crustum\Prompts\Console\Helper\ConfirmHelper instead.');
}

class_alias(ConfirmHelper::class, 'Crustum\Prompts\Command\Helper\ConfirmHelper');