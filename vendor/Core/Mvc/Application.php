<?php

namespace Core\Mvc;

use Phalcon\Mvc\Application as MvcApplication,
    Phalcon\Mvc\Dispatcher,
    Phalcon\DI\FactoryDefault as DiFactory,
    Phalcon\Http\Response,
    Phalcon\Config,
    Phalcon\Debug,
    Core\Exception\ExecutionException,
    Exception;

class Application extends MvcApplication
{
    /**
     * @var boolean
     */
    protected static $debugMode = false;

    /**
     * @var array
     */
    protected $defaultBootstrapListeners = [
        // bootstrap:init
        'Core\Bootstrap\RegisterModulesPathsListener',
        'Core\Bootstrap\RegisterModulesListener',
        'Core\Bootstrap\LoadModulesListener',
        'Core\Bootstrap\RegisterAssetsListener',
        'Core\Bootstrap\RegisterUrlListener',
        'Core\Bootstrap\RegisterViewListener',

        // bootstrap:beforeMergeConfig

        // bootstrap:mergeConfig
        'Core\Bootstrap\MergeGlobConfigListener',
        'Core\Bootstrap\MergeModulesConfigListener',

        // bootstrap:afterMergeConfig
        'Core\Bootstrap\RegisterDIListener',
        'Core\Bootstrap\ConfigCacheListener',
        'Core\Bootstrap\RegisterRoutesListener',
        'Core\Bootstrap\RegisterViewStrategyListener',

        // bootstrap:bootstrapModules
        'Core\Bootstrap\BootstrapModulesListener',
    ];

    /**
     * @param boolean $flag
     * @return void
     */
    public static function setDebugMode($flag = true)
    {
        $reportingLevel = $flag ? E_ALL | E_STRICT : 0;
        error_reporting($reportingLevel);

        static::$debugMode = (bool) $flag;
    }

    /**
     * @return boolean
     */
    public static function isDebugMode()
    {
        return static::$debugMode;
    }

    /**
     * @param array $configuration
     * @return Core\Mvc\Application
     */
    public static function init($configuration = [])
    {
        static $application;

        if ($application instanceof Application) {
            return $application;
        }

        $config = new Config($configuration);
        $di = new DiFactory();
        $di->setShared('config', $config);

        $application = new Application();
        $application->setDI($di);

        $eventsManager = $di->getShared('eventsManager');
        $application->setEventsManager($eventsManager);

        $dispatcher = new Dispatcher();
        $dispatcher->setEventsManager($eventsManager);
        $di->setShared('dispatcher', $dispatcher);

        /**
         * Listen for execution errors.
         */
        $eventsManager->attach('dispatch', new ExecutionException($di));

        return $application->bootstrap();
    }

    /**
     * @return Core\Mvc\Application
     */
    public function bootstrap()
    {
        $eventsManager = $this->getEventsManager();

        foreach ($this->defaultBootstrapListeners as $listener) {
            $listener = new $listener();
            $eventsManager->attach('bootstrap', $listener);
        }

        return $this;
    }

    /**
     * @param string $url optional
     * @return Phalcon\Http\ResponseInterface
     */
    public function handle($url = '')
    {
        try {
            $di = $this->getDI();
            $eventsManager = $this->getEventsManager();
            $eventsManager->fire('bootstrap:init', $this);
            $eventsManager->fire('bootstrap:beforeMergeConfig', $this);
            $eventsManager->fire('bootstrap:mergeConfig', $this);
            $eventsManager->fire('bootstrap:afterMergeConfig', $this);
            $eventsManager->fire('bootstrap:bootstrapModules', $this);
            $eventsManager->fire('bootstrap:beforeHandle', $this);

            return parent::handle($url);
        } catch (Exception $e) {
            if ($this->isDebugMode()) {
                (new Debug())->onUncaughtException($e);
            }
            return new Response();
        }
    }
}
