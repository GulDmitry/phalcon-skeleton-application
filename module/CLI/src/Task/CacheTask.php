<?php

namespace CLI\Task;

use Phalcon\Cli\Task,
    Symfony\Component\Console\Output\ConsoleOutput;

class CacheTask extends Task
{
    public function clearAllAction($route)
    {
        $output = new ConsoleOutput();
        $progress = $this->console->getHelperSet()->get('progress');
        $toClear = [
            'templates' => [
                'dir' => $this->config->templateOptions->compiledPath,
            ],
            'languages' => [
                'dir' => $this->config->multilanguage->languageCache->storage->options->cacheDir,
            ],
            'css' => [
                'dir' => PUBLIC_PATH . '/css/',
                'subst' => '.compiled.css',
            ],
            'js' => [
                'dir' => PUBLIC_PATH . '/js/',
                'subst' => '.compiled.js',
            ],
        ];
        $whiteList = [
            '.gitignore',
            '.gitkeep',
        ];

        foreach ($toClear as $cat => $data) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($data['dir']),
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            $output->writeln("Removing <info>$cat</info> cache...");
            $progress->start($output, iterator_count($iterator));

            foreach ($iterator as $fileInfo) {
                if ($fileInfo->isDir() || in_array($fileInfo->getFilename(), $whiteList)) {
                    $progress->advance();
                    continue;
                }
                if (isset($data['subst'])) {
                    if (strrpos($fileInfo->getFilename(), $data['subst']) !== false) {
                    unlink($fileInfo->getPathname());
                    }
                    $progress->advance();
                    continue;
                }
                unlink($fileInfo->getPathname());
                $progress->advance();
            }
            $progress->finish();
        }
    }
}
