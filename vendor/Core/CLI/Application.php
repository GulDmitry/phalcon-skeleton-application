<?php

namespace Core\CLI;

use Phalcon\CLI\Console as ConsoleApplication,
    Phalcon\DI\FactoryDefault\CLI as DiFactory,
    Phalcon\Config,
    Phalcon\Exception,
    Core\Exception\DomainException;

class Application extends ConsoleApplication
{
    /**
     * @var array
     */
    protected $defaultBootstrapListeners = [
        // bootstrap:init
        'Core\Bootstrap\RegisterModulesPathsListener',
        'Core\Bootstrap\RegisterModulesListener',
        'Core\Bootstrap\LoadModulesListener',

        // bootstrap:beforeMergeConfig

        // bootstrap:mergeConfig
        'Core\Bootstrap\MergeGlobConfigListener',
        'Core\Bootstrap\MergeModulesConfigListener',
        // bootstrap:afterMergeConfig
        'Core\Bootstrap\RegisterDIListener',
        'Core\Bootstrap\RegisterCLIRoutesListener',
        // bootstrap:bootstrapModules
        'Core\Bootstrap\BootstrapModulesListener',
    ];

    /**
     * @param array $configuration
     * @return Core\CLI\Application
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

        return $application->bootstrap();
    }

    /**
     * @return Core\CLI\Application
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
     * Register an array of modules present in the application
     *
     * @param array $modules
     * @param boolean $stab Stab for save the same number of parameters with MVC.
     */
    public function registerModules($modules, $stab = null)
    {
        parent::registerModules($modules);
    }

    /**
     * @param array $arguments
     * @return array
     * @throws \Core\Exception\DomainException
     */
    protected function convertArgsToCLIFormat(array $arguments)
    {
        $di = $this->getDI();
        $config = $di->getShared('config');

        if (!isset($arguments['route'])) {
            $arguments['route'] = 'help';
        }

        if (!isset($config->router->routes[$arguments['route']])) {
            throw new DomainException(sprintf(
                "The route '%s' cannot be found.",
                $arguments['route']
            ));
        }
        $routeData = $config->router->routes[$arguments['route']]->defaults;
        $arguments['task'] = $routeData->namespace . '\\' . ucfirst($routeData->task);
        $arguments['action'] = $routeData->action;
        if (isset($routeData->description)) {
            $arguments['description'] = $routeData->description;
        }

        return $arguments;
    }

    /**
     * @param array $arguments optional
     * @return mixed
     */
    public function handle(array $arguments)
    {
        try {
            $eventsManager = $this->getEventsManager();
            $eventsManager->fire('bootstrap:init', $this);
            $eventsManager->fire('bootstrap:beforeMergeConfig', $this);
            $eventsManager->fire('bootstrap:mergeConfig', $this);
            $eventsManager->fire('bootstrap:afterMergeConfig', $this);
            $eventsManager->fire('bootstrap:bootstrapModules', $this);
            $eventsManager->fire('bootstrap:beforeHandle', $this);

            $arguments = $this->convertArgsToCLIFormat($arguments);

            return parent::handle($arguments);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit(255);
        }
    }
}
