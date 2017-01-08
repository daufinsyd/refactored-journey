<?php

namespace RASP\RaspBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RASPRaspBundle:Default:index.html.twig');
    }
    public function adminIndexAction()
    {
        return $this->render('RASPRaspBundle::admin.html.twig');
    }
}
