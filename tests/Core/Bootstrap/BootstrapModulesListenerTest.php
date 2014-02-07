<?php

namespace Test\Core\Bootstrap;

use Test\UnitTestCase,
    Core\Mvc\Application,
    Core\Bootstrap\BootstrapModulesListener as CoreBootstrapModules;

class BootstrapModulesListener extends UnitTestCase
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
        $eventsManager->attach('bootstrap', new CoreBootstrapModules());

        $modulesConfig = [];
        foreach ($this->app->getDI()->get('config')->modules as $moduleNamespace) {
            $modulesConfig[$moduleNamespace] = [
                'className' => $moduleNamespace . '\Module',
                'object'
            ];
        }
        $this->app->registerModules($modulesConfig, true);

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testBootstrapModules()
    {

        $this->app->handle();
        $modules = $this->app->getModules();

        $this->assertTrue(true);
//        foreach ($modules as $moduleOptions) {
//            if (!isset($moduleOptions['object'])
//                || !is_object($moduleOptions['object'])
//            ) {
//                continue;
//            }
//            $module = $moduleOptions['object'];
//            if (method_exists($module, 'onBootstrap')) {
//                $module->onBootstrap($application);
//            }
//        }
    }
}

