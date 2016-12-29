<?php

namespace GUFR\UFRBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GUFRUFRBundle:Default:index.html.twig');
    }
}
