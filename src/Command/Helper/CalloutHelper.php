<?php
declare(strict_types=1);

use Cake\Core\Configure;
use Crustum\Prompts\Console\Helper\CalloutHelper;
use function Cake\Core\deprecationWarning;

if (version_compare(Configure::version(), '5.4.0', '>=')) {
    deprecationWarning('5.4.0', 'Crustum\Prompts\Command\Helper\CalloutHelper is deprecated. Use Crustum\Prompts\Console\Helper\CalloutHelper instead.');
}

class_alias(CalloutHelper::class, 'Crustum\Prompts\Command\Helper\CalloutHelper');