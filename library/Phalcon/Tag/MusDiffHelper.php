<?php
namespace Phalcon\Tag;

use Phalcon\Tag as PhalconTag;

class MusDiffHelper extends PhalconTag
{
    static public function getCurrentRouteName()
    {
        $di = self::getDI();
        return $di->get('router')->getMatchedRoute()->getName();
    }
}
