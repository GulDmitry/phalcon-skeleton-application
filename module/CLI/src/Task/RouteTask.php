<?php

namespace CLI\Task;

use Phalcon\Cli\Task,
    Symfony\Component\Console\Output\ConsoleOutput;

class RouteTask extends Task
{
    public function availableMvcRoutesAction($route)
    {
        $output = new ConsoleOutput();
        $table = $this->console->getHelperSet()->get('table');
        $table->setHeaders(array('Name', 'Url', 'Controller', 'Action'));

        foreach ($this->config->modules as $moduleName) {
            $class = $moduleName . '\Module';
            $tableOutput = [];

            $object = new $class();
            $config = $object->getConfig();

            foreach ($config['router']['routes'] as $routeName => $data) {
                $tableOutput[] = [
                    $routeName,
                    $data['route'],
                    $data['defaults']['controller'],
                    $data['defaults']['action'],
                ];
            }
            $output->writeln('');
            $output->writeln("Module: <comment>$moduleName</comment>");
            $table->setRows($tableOutput);
            $table->render($output);
        }
    }
}
