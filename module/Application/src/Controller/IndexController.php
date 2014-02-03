<?php

namespace Application\Controller;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        $this->view->setVar('message', $this->t->_('LBL_GREETING'));
    }

    public function notFoundAction()
    {

    }
}
