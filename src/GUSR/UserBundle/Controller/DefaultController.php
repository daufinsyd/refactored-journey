<?php

namespace GUSR\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GUSRUserBundle:Default:index.html.twig');
    }
}
