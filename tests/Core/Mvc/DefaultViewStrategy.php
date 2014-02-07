<?php

namespace Core\Mvc;

class DefaultViewStrategy
{
    protected $config;

    protected $view;

    public function __construct($config, $view)
    {
        $this->config = $config;
        $this->view = $view;
    }

    public function beforeRequest($event, $dispatcher)
    {
        $this->registerView($event, $dispatcher);
    }

    public function beforeDispatch($event, $dispatcher)
    {
        $this->registerView($event, $dispatcher);
    }

    protected function registerView($event, $dispatcher)
    {
        $module = strtolower($dispatcher->getModuleName());
        $namespace = strtolower($dispatcher->getNamespaceName());

        if (false === strpos($namespace, $module)) {
            $pos = strpos($namespace, '\\');
            if (false === $pos) {
                $module = $namespace;
            } else {
                $module = substr($namespace, 0, $pos);
            }
        }

        if (! isset($this->config['viewStrategy'][$module])) {
            return;
        }

        $options = $this->config['viewStrategy'][$module];

        if (isset($options['viewDir'])) {
            $this->view->setViewsDir($options['viewDir']);
        }

        if (isset($options['layoutsDir'])) {
            $this->view->setLayoutsDir($options['layoutsDir']);
        }

        if (isset($options['defaultLayout'])) {
            $this->view->setLayout($options['defaultLayout']);
        }
    }
}
