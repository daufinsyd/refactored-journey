<?php
/**
 * Created by PhpStorm.
 * User: sydney_manjaro
 * Date: 08/01/17
 * Time: 16:06
 */

namespace RASP\RaspBundle\Controller;
use RASP\RaspBundle\Form\User\RaspType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

// Types
use RASP\RaspBundle\Form\User\RaspberryType;

// Entities
use RASP\RaspBundle\Entity\Raspberry;

// Repo
use RASP\RaspBundle\Entity\RaspberryRepository;

class RaspController extends Controller
{
    public function showRaspAction($rasp_id,  Request $request)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $rasp = $this->getDoctrine()->getRepository("RASPRaspBundle:Raspberry")->find($rasp_id);

        return $this->render("RASPRaspBundle:Rasp:rasp.html.twig", array('rasp' => $rasp, 'loggedInUser' => $loggedInUser));
    }

    public function listRaspAction($ufr_id)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $ufr = $this->getDoctrine()->getRepository('RASPRaspBundle:Ufr')->find($ufr_id);
        $listRasp = $this->getDoctrine()->getRepository("RASPRaspBundle:Raspberry")->findBy(array("ufr" => $ufr));
        return $this->render("@RASPRasp/Rasp/listRasp.html.twig", array('listRasp' => $listRasp, 'loggedInUser' => $loggedInUser));
    }

    public function editRaspAction($rasp_id, Request $request)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $listUfr = $this->getDoctrine()->getRepository('RASPRaspBundle:Ufr')->findAll();
        $rasp = $this->getDoctrine()->getRepository('RASPRaspBundle:Raspberry')->find($rasp_id);

        $form = $this->createForm(RaspType::class, $rasp, array('listUfr' => $listUfr));
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $rasp = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($rasp);
            $em->flush();

            return $this->render("RASPRaspBundle:Rasp:rasp.html.twig", array('rasp' => $rasp, 'rasp_id' => $rasp_id, 'loggedInUser' => $loggedInUser));
        }
        return $this->render("@RASPRasp/Rasp/editRasp.html.twig", array('form' => $form->createView(), 'loggedInUser' => $loggedInUser));
    }

}