<?php

namespace Core\Bootstrap;

class ConfigCacheListener
{
    /**
     * @var boolean
     */
    protected $enableWritingCache = false;

    protected function afterMergeConfig($event, $application)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        if (!$config->configCache->enabled) {
            return;
        }

        $storage = $config->configCache->storage;
        $lifetime = $config->configCache->lifetime;

        $containerClass = $storage->frontend;
        $container = new $containerClass($lifetime);

        $storageClass = $storage->backend;
        $cache = new $storageClass($container, (array)$storage->options);

        $cachedConfig = $cache->get($storage->options->key, $lifetime);

        if (!empty($cachedConfig)) {
            return;
        }

        $cache->save($storage->options->key, $config, $lifetime);
    }
}
