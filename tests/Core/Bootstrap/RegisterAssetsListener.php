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
        $this->setupCss($application, $manager, false);
    }

    /**
     * @param \Phalcon\Mvc\Application $application
     * @param Manager $manager
     */
    public function setupJS(\Phalcon\Mvc\Application $application, \Phalcon\Assets\Manager $manager)
    {
        $di = $application->getDI();
        $config = $di->get('config');
        $finalName = 'header.compiled.js';

        $jsCollection = $manager->collection('headerJs')
            ->setTargetPath(PUBLIC_PATH . '/js/' . $finalName)
            ->setTargetUri('js/' . $finalName)
            ->setFilters([new None()])
        ;

        if ($config->assetsCache->enabled && file_exists(PUBLIC_PATH . '/js/' . $finalName)) {
            $jsCollection->addJs(PUBLIC_PATH . '/js/' . $finalName);
            return;
        }

        $jsCollection
            // This is a remote resource that does not need filtering
//              ->addJs('code.jquery.com/jquery-1.10.0.min.js', true, false)
            // These are local resources that must be filtered.
            ->addJs(VENDOR_PATH . '/jquery/jquery.js')
            ->addJs(VENDOR_PATH . '/twitter/bootstrap/dist/js/bootstrap.js')
            ->addJs(VENDOR_PATH . '/carhartl/jquery-cookie/jquery.cookie.js')
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
    public function setupCss(\Phalcon\Mvc\Application $application, \Phalcon\Assets\Manager $manager, $isLess = false)
    {
        $di = $application->getDI();
        $config = $di->get('config');
        $finalName = 'header.compiled.css';

        $cssCollection = $manager->collection('headerCss')
            ->setTargetPath(PUBLIC_PATH . '/css/' . $finalName)
            ->setTargetUri('css/' .  $finalName)
            ->setFilters([new None()])
        ;

        if ($config->assetsCache->enabled && file_exists(PUBLIC_PATH . '/css/' . $finalName)) {
            $cssCollection->addCss(PUBLIC_PATH . '/css/' . $finalName);
            return;
        }

        if ($isLess) {
            // TODO: check when https://github.com/leafo/lessphp/issues/498 is fixed.
            $lessPhp = new \lessc();
            $lessPhp->setVariables([
                'twitterBootstrap' => '"' . VENDOR_PATH . '/twitter/bootstrap/less/bootstrap.less' . '"',
            ]);
            $lessPhp->compileFile(PUBLIC_PATH . '/less/custom.less', PUBLIC_PATH . '/css/' . $finalName);
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
