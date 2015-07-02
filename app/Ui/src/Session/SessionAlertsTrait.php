<?php
namespace RacingUi\Session;

trait SessionAlertsTrait
{
    public function getAndUnsetErrors()
    {
        $errors = $this->app['session']->get('errors');
        if (count($errors)) {
            $this->app['session']->remove('errors');
        }
        return $errors;
    }

    public function getAndUnsetMessages()
    {
        $message = $this->app['session']->get('message');
        if (!empty($message)) {
            $this->app['session']->remove('message');
        }
        return $message;
    }
}
