<?php

namespace Core\Bootstrap;

use Phalcon\Tag\MusDiffHelper as Tag;

class RegisterTagListener
{
    public function init($event, $application)
    {
        $di = $application->getDI();
        $di->setShared('tag', new Tag());
    }
}
