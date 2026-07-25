<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\FormHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\FormHelper is deprecated. Use Crustum\Prompts\Console\Helper\FormHelper instead.');
}

class_alias(FormHelper::class, 'Crustum\Prompts\Command\Helper\FormHelper');