<?php

namespace CLI\Task;

use Phalcon\Cli\Task;

class MainTask extends Task
{
    public function mainAction()
    {
        var_dump('The main CLI task!');
    }

    public function helpAction()
    {
        echo 'Available routes' . PHP_EOL;
        var_dump((array)$this->config->router->routes);
    }

    /**
     * @param $route
     * @param array $params
     */
    public function testAction($route, array $params)
    {
        echo sprintf('route name is %s', $route) . PHP_EOL;
        echo sprintf('hello %s', $params[0]) . PHP_EOL;
        echo sprintf('best regards, %s', $params[1]) . PHP_EOL;
    }
}
