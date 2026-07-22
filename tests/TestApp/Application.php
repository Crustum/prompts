<?php
declare(strict_types=1);

namespace TestApp;

use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;
use Override;

/**
 * Minimal CakePHP application for Prompts plugin tests.
 */
class Application extends BaseApplication
{
    /**
     * Bootstrap the test application and load the Prompts plugin.
     *
     * @return void
     */
    #[Override]
    public function bootstrap(): void
    {
        $this->addPlugin('Crustum/Prompts', [
            'path' => ROOT . DS,
            'bootstrap' => true,
        ]);

        parent::bootstrap();
    }

    /**
     * Define middleware for the test application.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue Middleware queue
     * @return \Cake\Http\MiddlewareQueue
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue;
    }
}
