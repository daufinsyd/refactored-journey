<?php

namespace GPROF\ProfileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GPROFProfileBundle:Default:index.html.twig');
    }
}
