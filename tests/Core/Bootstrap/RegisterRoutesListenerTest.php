<?php

namespace Test\Core\Bootstrap;

use Test\UnitTestCase;
use Phalcon\Config;
use Core\Bootstrap\RegisterRoutesListener as CoreRegisterRoutesListener;

class RegisterRoutesListener extends UnitTestCase
{
    /**
     * @var \Core\Mvc\Application
     */
    public $app;

    protected function setUp()
    {
        $this->app = $this->initMvcApplication();
        $eventsManager = $this->app->getEventsManager();
        $eventsManager->detachAll('bootstrap');
        $eventsManager->attach('bootstrap', new CoreRegisterRoutesListener());

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @dataProvider providerRouteData
     * @expectedException Core\Exception\DomainException
     */
    public function testRoutesExceptionsModules($route)
    {
        $config = $this->app->getDI()->get('config');
        $config->merge(
            new Config([
                'router' => [
                    'routes' => [
                        'testing_exception' => $route,
                    ]
                ]

            ])
        );
        $this->app->handle();
    }

    public static function providerRouteData()
    {
        return [
            [
                [
                    // Route omitted
                    'defaults' => [
                        'module' => 'Application',
                        'namespace' => 'Application\Controller',
                    ],
                ],
            ],
            [
                [
                    'route' => '/some_url',
                    'defaults' => [
                        // Module omitted.
                        'namespace' => 'Application\Controller',
                    ],
                ],
            ],
            [
                [
                    'route' => '/some_url',
                    'defaults' => [
                        'module' => 'Application',
                        // Namespace omitted.
                    ],
                ],
            ],
        ];
    }
}

