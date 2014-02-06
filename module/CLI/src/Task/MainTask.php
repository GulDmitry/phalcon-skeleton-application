<?php

namespace CLI\Task;

use Phalcon\Cli\Task,
    Symfony\Component\Console\Output\ConsoleOutput;

class MainTask extends Task
{
    public function mainAction($route)
    {
        $output = new ConsoleOutput();
        $formatter = $this->console->getHelperSet()->get('formatter');
        $formattedLine = $formatter->formatSection(
            $route,
            'This is the main task'
        );
        $output->writeln($formattedLine);
    }

    public function helpAction()
    {
        $output = new ConsoleOutput();
        $tableOutput = [];
        $table = $this->console->getHelperSet()->get('table');
        $table->setHeaders(array('Name', 'Task', 'Action', 'Description'));

        foreach ($this->config->router->routes as $name => $data) {
            $tableOutput[] = [
                $name,
                $data->defaults->task,
                $data->defaults->action,
                $data->defaults->description,
            ];
        }

        $table->setRows($tableOutput);
        $table->render($output);
        $output->writeln(
            'Usage: php cli.php <info>Name</info> <bg=cyan>option1</bg=cyan> <bg=cyan>option2</bg=cyan> ...'
        );
    }

    /**
     * @param $route
     * @param array $params
     */
    public function testAction($route, array $params)
    {
        $output = new ConsoleOutput();
        $formatter = $this->console->getHelperSet()->get('formatter');

        if (!isset($params[0], $params[1])) {
            $errorMessages = array('Error!', 'Please define two options.');
            $formattedBlock = $formatter->formatBlock($errorMessages, 'error');
            $output->writeln($formattedBlock);
            // OR
            $output->writeln('<error>Two options should be defined.</error>'); // White text on a red background.
            return;
        }
        $output->writeln("Route name is <info>$route</info>"); // Green.
        $output->writeln("Hello <comment>$params[0]</comment>"); // Yellow.
        $output->writeln("Best regards <question>$params[1]</question>"); // Black text on a cyan background.
    }
}
