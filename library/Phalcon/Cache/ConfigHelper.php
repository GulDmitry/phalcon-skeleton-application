<?php
namespace Phalcon\Cache;

use Phalcon\Config as PhalconConfig;
use Core\Exception\DomainException;

/**
 * Phalcon\Config
 *
 * Set up Backend and Frontend objects from config.
 *
 * Config example:
 *<code>
 *  'SomeCache' => [
 *     'enabled' => true,
 *      'lifetime' => 86400,
 *      'storage' => [
 *          'backend' => '\Phalcon\Cache\Backend\File',
 *          'frontend' => '\Phalcon\Cache\Frontend\Data',
 *          'options' => [
 *              'key' => 'some_key',
 *              'cacheDir' => '/path/to/cache/dir/',
 *          ],
 *      ],
 *  ],
 *</code>
 *
 */
class ConfigHelper
{
    /**
     * @var \Phalcon\Cache\Frontend
     */
    protected $frontend;

    /**
     * @var\Phalcon\Cache\Backend
     */
    protected $backend;

    /**
     * @var string Cache key.
     */
    protected $key;

    /**
     * @var int Cache lifetime.
     */
    protected $lifetime;

    /**
     * @var Bool
     */
    protected $isEnabled;

    /**
     * @param $config array
     */
    public function __construct(array $config)
    {
        $this->checkIncomingConfig($config);

        $this->isEnabled = $config['enabled'];
        $this->lifetime = $config['lifetime'];

        $frontendName = $config['storage']['frontend'];
        $this->frontend = new $frontendName($this->lifetime);

        $backendName = $config['storage']['backend'];
        $this->backend = new $backendName($this->frontend, $config['storage']['options']);

        $this->key = $config['storage']['options']['key'];
    }


    /**
     * @return Bool
     */
    public function isEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Get cached value.
     *
     * @param $postfix string
     * @return mixed Null if cache disabled or cached value.
     */
    public function getValue($postfix)
    {
        if ($this->isEnabled()) {
            return $this->backend->get($this->key . $postfix, $this->lifetime);
        }
    }

    /**
     * Save the value if cache is enabled.
     * @param $postfix string
     * @param $value mixed
     */
    public function setValue($postfix, $value)
    {
        if ($this->isEnabled()) {
            $this->backend->save($this->key . $postfix, $value, $this->lifetime);
        }
    }

    protected function checkIncomingConfig(array $config)
    {
        if (!isset($config['enabled'])) {
            throw new DomainException(
                "Missing default option 'enabled'"
            );
        }
        if (!isset($config['lifetime'])) {
            throw new DomainException(
                "Missing default option 'lifetime'"
            );
        }
        if (!isset($config['storage']['frontend'])) {
            throw new DomainException(
                "Missing default option 'frontend'"
            );
        }
        if (!isset($config['storage']['backend'])) {
            throw new DomainException(
                "Missing default option 'backend'"
            );
        }
        if (!isset($config['storage']['options']['key'])) {
            throw new DomainException(
                "Missing default option 'key'"
            );
        }
    }
}
