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

    public function profileAction(){
        return new Response("hello world !");
    }

    public function usersAction(){
        return $this->render('LOGLoginBundle:GestionViews:users.html.twig');
    }

    public function groupAction(){
        return $this->render('LOGLoginBundle:GestionViews:group.html.twig');
    }

}