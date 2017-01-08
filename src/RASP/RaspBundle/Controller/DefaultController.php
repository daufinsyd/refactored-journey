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
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('RASPRaspBundle::admin.html.twig', array('loggedInUser' => $loggedInUser));
    }
}
