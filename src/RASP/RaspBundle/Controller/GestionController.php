<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 02/01/2017
 * Time: 14:54
 * Path : RCCF/src/LOG/LoginBundle/Controller/GestionController.php
 */

namespace RASP\RaspBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// for absolute path
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

// for JsonsResponse
use Symfony\Component\HttpFoundation\JsonResponse;

class GestionController extends Controller {
    // Actions for RCCF admin

    public function profileAction(){
        return new Response("hello world !");
    }

    public function usersAction(){
        // List users
        return $this->render('RASPRaspBundle:User/Gestion:users.html.twig');
    }

    public function userAction($id)
    {
        // Show specific user
        $user = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->find(1);
        return $this->render("RASPRaspBundle:User/Gestion:user.html.twig", array("user" => $user));
    }

    public function editUserAction($id)
    {
        // Show specific user
        $user = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->find(1);
        $listUfr = $this->getDoctrine()->getRepository("RASPRaspBundle:Ufr")->findAll();
        return $this->render("RASPRaspBundle:User/Gestion:editUser.html.twig", array("user" => $user, "listUfr" => $listUfr));
    }

    public function createUser()
    {
        // Create a new user
        return $this;
    }

    public function groupAction(){
        return $this->render('RASPRaspBundle:User/Gestion:group.html.twig');
    }

}