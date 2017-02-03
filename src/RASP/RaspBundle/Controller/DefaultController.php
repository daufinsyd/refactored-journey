<?php

namespace RASP\RaspBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $securityContext = $this->get('security.authorization_checker');
        if ( $this->isGranted('ROLE_USER') or $this->isGranted('ROLE_ADMIN')) {
            return $this->render('RASPRaspBundle:Default:index.html.twig', array('loggedInUser' => $loggedInUser));
        }
        else {
            return $this->redirect("/login");
        }
    }

    public function adminIndexAction()
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('RASPRaspBundle::admin.html.twig', array('loggedInUser' => $loggedInUser));
    }
}
