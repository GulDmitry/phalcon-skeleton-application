<?php

namespace Core\Bootstrap;

use Phalcon\Assets\Manager,
    Phalcon\Assets\Filters\None,
    Phalcon\Assets\Filters\Cssmin,
    Phalcon\Assets\Filters\Jsmin;

class RegisterAssetsListener
{
    public function init($event, $application)
    {
        $di = $application->getDI();
        $di->setShared('assets', new Manager());
    }

    public function afterMergeConfig($event, $application)
    {
        $di = $application->getDI();
        $manager = $di->getShared('assets');

        $this->setupJS($application, $manager);
        $this->setupCss($application, $manager);
    }

    /**
     * @param \Phalcon\Mvc\Application $application
     * @param Manager $manager
     */
    public function setupJS(\Phalcon\Mvc\Application $application, \Phalcon\Assets\Manager $manager)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        $jsCollection = $manager->collection('headerJs')
            ->setTargetPath(PUBLIC_PATH . '/js/header.js')
            ->setTargetUri('js/header.js')
            ->setFilters([new None()])
        ;

        if ($config->assetsCache->enabled && file_exists(PUBLIC_PATH . '/js/header.js')) {
            $jsCollection->addJs(PUBLIC_PATH . '/js/header.js');
            return;
        }

        $jsCollection
            // This is a remote resource that does not need filtering
//              ->addJs('code.jquery.com/jquery-1.10.0.min.js', true, false)
            // These are local resources that must be filtered.
            ->addJs(VENDOR_PATH . '/jquery/jquery.js')
            ->addJs(VENDOR_PATH . '/twitter/bootstrap/dist/js/bootstrap.js')
            ->addJs(PUBLIC_PATH . '/js/custom.js')
            ->join(true)
        ;

        if (!$application->isDebugMode()) {
            $jsCollection->setFilters([new Jsmin()]);
        }
    }

    /**
     * @param \Phalcon\Mvc\Application $application
     * @param Manager $manager
     */
    public function setupCss(\Phalcon\Mvc\Application $application, \Phalcon\Assets\Manager $manager)
    {
        $di = $application->getDI();
        $config = $di->get('config');

        $cssCollection = $manager->collection('headerCss')
            ->setTargetPath(PUBLIC_PATH . '/css/header.css')
            ->setTargetUri('css/header.css')
            ->setFilters([new None()])
        ;

        if ($config->assetsCache->enabled && file_exists(PUBLIC_PATH . '/css/header.css')) {
            $cssCollection->addCss(PUBLIC_PATH . '/css/header.css');
            return;
        }

        $cssCollection
            ->addCss(VENDOR_PATH . '/twitter/bootstrap/dist/css/bootstrap.css')
            ->addCss(PUBLIC_PATH . '/css/custom.css')
            ->join(true)
        ;

        if (!$application->isDebugMode()) {
            $cssCollection->setFilters([new Cssmin()]);
        }
    }
}
