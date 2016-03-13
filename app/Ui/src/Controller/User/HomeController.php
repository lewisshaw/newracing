<?php
namespace RacingUi\Controller\User;

use RacingUi\Session\SessionAlertsTrait;
use Silex\Application;

class HomeController
{
    use SessionAlertsTrait;

    private $templater;
    private $app;

    public function __construct($templater, Application $app)
    {
        $this->templater = $templater;
        $this->app = $app;
    }

    public function index()
    {
        $errors = $this->getAndUnsetErrors();
        $message = $this->getAndUnsetMessages();


        return $this->templater->render('user/home.twig', [
            'title' => 'Racing | Home',
            'errors' => $errors,
            'message' => $message,
        ]);
    }
}
