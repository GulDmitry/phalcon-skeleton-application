<?php

namespace Core\Exception;

use Phalcon\Mvc\Dispatcher,
    Phalcon\Events\Event;

class ExecutionException
{
    public function beforeException(Event $event, Dispatcher $dispatcher, $exception)
    {
        switch ($exception->getCode()) {
            case Dispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                $dispatcher->forward([
                    'module' => 'Application',
                    'namespace' => 'Application\Controller',
                    'controller' => 'index',
                    'action' => 'notFound',
                ]);
                return false;
            break;
        }
    }
}