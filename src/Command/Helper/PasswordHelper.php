<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\PasswordHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\PasswordHelper is deprecated. Use Crustum\Prompts\Console\Helper\PasswordHelper instead.');
}

class_alias(PasswordHelper::class, 'Crustum\Prompts\Command\Helper\PasswordHelper');