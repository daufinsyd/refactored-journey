<?php

namespace LOG\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LOGLoginBundle:Default:index.html.twig');
    }
}
