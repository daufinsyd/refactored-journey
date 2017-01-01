<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 29/12/2016
 * Time: 15:38
 * Path : RCCF/src/LOG/LoginBundle/Controller/SigninController.php
 */

namespace LOG\LoginBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// for absolute path
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

// for JsonsResponse
use Symfony\Component\HttpFoundation\JsonResponse;



class SigninController extends Controller {


    public function indexAction(){
        //return $this->render('LOGLoginBundle:Signin:index.html.twig');
        return new response("Hello world !");
    }

}

?>