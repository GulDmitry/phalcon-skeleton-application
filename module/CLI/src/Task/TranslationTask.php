<?php

namespace CLI\Task;

use Phalcon\Cli\Task,
    Symfony\Component\Console\Output\ConsoleOutput;

class TranslationTask extends Task
{
    public function getUntranslatedlabelsAction($route)
    {
        $output = new ConsoleOutput();
        $table = $this->console->getHelperSet()->get('table');
        $table->setHeaders(array('Label', 'Default Value'));

        $defaultFile = $this->config->multilanguage->path . $this->config->multilanguage->defaultLanguage . '.php';
        $defaultSet = require $defaultFile;

        foreach ($this->config->multilanguage->languages as $key => $name) {

            if ($key == $this->config->multilanguage->defaultLanguage) {
                continue;
            }

            $setForCheck = require $this->config->multilanguage->path . $key . '.php';

            $diff = array_diff_key($defaultSet, $setForCheck);

            if (empty($diff)) {
                continue;
            }

            $output->writeln('');
            $output->writeln("Language: <comment>$key</comment>");

            array_walk(
                $diff,
                function (&$val, $key) {
                    $val = [$key, $val];
                }
            );

            $table->setRows($diff);
            $table->render($output);
        }
    }
}
