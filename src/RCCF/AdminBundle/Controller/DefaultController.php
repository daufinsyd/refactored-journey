<?php

namespace RCCF\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RCCfAdminBundle:Default:index.html.twig');
    }
}
