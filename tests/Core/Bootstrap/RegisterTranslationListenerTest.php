<?php

namespace Test\Core\Bootstrap;

use Test\UnitTestCase;
use Core\Bootstrap\RegisterTranslationListener as CoreRegisterTranslationListener;

class RegisterTranslationListener extends UnitTestCase
{
    /**
     * @var \Phalcon\Config
     */
    protected $configContainer;

    protected function setUp()
    {
        $this->initMvcApplication();
        $eventsManager = $this->app->getEventsManager();
        $eventsManager->detachAll('bootstrap');
        $eventsManager->attach('bootstrap', new CoreRegisterTranslationListener());

        $this->configContainer = $this->app->getDI()->get('config');
        $this->configContainer->multilanguage = [
            'enabled' => true,
            'languages' => [
                'en' => 'English',
                'ru' => 'Русский',
            ],
            'path' => sys_get_temp_dir() . '/',
            'defaultLanguage' => 'en',
            'languageCache' => [
                'enabled' => true,
                'lifetime' => 86400,
                'storage' => [
                    'backend' => '\Phalcon\Cache\Backend\File',
                    'frontend' => '\Phalcon\Cache\Frontend\Data',
                    'options' => [
                        'key' => 'language',
                        'cacheDir' => APPLICATION_PATH . '/data/cache/language/',
                    ],
                ],
            ],
        ];

        file_put_contents(
            $this->configContainer->multilanguage->path . 'en.php',
            '<?php
                return [
                    "LBL_TRANSLATED" => "translated_en",
                    "LBL_UNTRANSLATED" => "untranslated",
                ];
            '
        );
        file_put_contents(
            $this->configContainer->multilanguage->path . 'ru.php',
            '<?php
                return [
                    "LBL_TRANSLATED" => "translated_ru",
                ];
            '
        );

        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testEmptyContentWhenMultilanguageIsDisabled()
    {
        $this->configContainer->multilanguage->enabled = false;

        $this->app->handle();

        $this->assertFalse($this->app->getDI()->get('t')->exists('LBL_TRANSLATED'));
    }

    public function testTranslations()
    {
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'ru';

        $this->app->handle();

        $translation = $this->app->getDI()->get('t');

        $this->assertEquals('translated_ru', $translation->_('LBL_TRANSLATED'));
        $this->assertEquals('untranslated', $translation->_('LBL_UNTRANSLATED'));
    }
}
