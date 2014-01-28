<?php

namespace Core\Bootstrap;

use Phalcon\Config;

class MergeGlobConfigListener
{
    public function mergeConfig($event, $application)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        if (isset($config['configGlobPaths'])) {
            $globPats = (array) $config['configGlobPaths'];
            foreach ($globPats as $path) {
                $files = glob($path, GLOB_BRACE);
                foreach ($files as $file) {
                    $item = require $file;
                    if ($item instanceof Config) {
                        $config->merge($item);
                        continue;
                    }
                    if (! is_array($item)) {
                        continue;
                    }
                    $item = new Config($item);
                    $config->merge($item);
                }
            }
        }
    }
}
