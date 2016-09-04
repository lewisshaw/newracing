<?php
namespace RacingUi\Controller\Api;

use Symfony\Component\HttpFoundation\Request;

class RaceImport
{

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function import(Request $request)
    {
        $token = $this->app['security.token_storage']->getToken();
        if (null !== $token) {
            $user = $token->getUser();
        }
        $encoder = $this->app['security.encoder_factory']->getEncoder($user);

// compute the encoded password for foo
        $password = $encoder->encodePassword('436tlsegr7ogslgs7ogsglhdfsgf7ysdghsdfighf', $user->getSalt());
        return $password;
    }
}
