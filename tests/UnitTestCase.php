<?php
namespace Test;

use \Phalcon\DI;
use \Phalcon\Test\UnitTestCase as PhalconTestCase;
use \Core\Mvc\Application as MvcApplication;
use \Core\Bootstrap\RegisterViewListener;
use \Core\Bootstrap\RegisterModulesPathsListener;


abstract class UnitTestCase extends PhalconTestCase
{
    /**
     * @var bool
     */
    private $loaded = false;

    /**
     * @var \Phalcon\Mvc\Application
     */
    protected $app;

    protected function setUp()
    {
        // Load any additional services that might be required during testing.
        $di = DI::getDefault();

        // Get any DI components here. If you have a config, be sure to pass it to the parent.

        parent::setUp($di);

        $this->loaded = true;
    }

    /**
     * Setup Mvc Application.
     *
     * @return \Core\Mvc\Application
     */
    public function initMvcApplication()
    {
        $app = MvcApplication::init(require APPLICATION_PATH . '/config/application.config.php');
        $viewListener = new RegisterViewListener();
        $pathListener = new RegisterModulesPathsListener();
        // Register module dirs.
        $pathListener->init(null, $app);
        // To avoid Phalcon\DI\Exception : Service 'view' wasn't found in the dependency injection container
        $viewListener->init(null, $app);

        $app->registerModules(
            [
                'Application' => [
                    'className' => 'Application\Module',
                ]
            ],
            true
        );

        $router = $app->getDI()->getShared('router');

        // Need at least one default route.
        $router->add('/', [
            'module' => 'Application',
            'namespace' => 'Application\Controller',
            'controller' => 'index',
            'action' => 'index',
        ])->setName('default_route_for_test');

        $this->app = $app;
        return $app;
    }

    /**
     * Check if the test case is setup properly
     * @throws \PHPUnit_Framework_IncompleteTestError;
     */
    public function __destruct()
    {
        if (!$this->loaded) {
            throw new \PHPUnit_Framework_IncompleteTestError('Please run parent::setUp().');
        }
    }
}
