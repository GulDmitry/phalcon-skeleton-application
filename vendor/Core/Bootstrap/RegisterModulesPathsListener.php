<?php

namespace Core\Bootstrap;

use Phalcon\Loader;

class RegisterModulesPathsListener
{
    public function init($event, $application)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        if (isset($config['modulePaths'])) {
            $paths = [];
            foreach ($config['modulePaths'] as $path) {
                $paths[] = realpath($path);
            }

            $loader = $application->getDI()->getShared('loader');
            // True - merges values.
            $loader->registerDirs($paths, true);
        }
    }
}
