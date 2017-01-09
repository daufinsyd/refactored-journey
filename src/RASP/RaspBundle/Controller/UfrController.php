<?php
/**
 * Created by PhpStorm.
 * User: sydney_manjaro
 * Date: 08/01/17
 * Time: 16:06
 */

namespace RASP\RaspBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

// Types
use RASP\RaspBundle\Form\User\UfrType;

// Entities
use RASP\RaspBundle\Entity\Ufr;

// Repo
use RASP\RaspBundle\Entity\UfrRepository;

class UfrController extends Controller
{
    public function createUfrAction(Request $request)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $ufr = new Ufr();

        $form = $this->createForm(UfrType::class, $ufr);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $ufr = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($ufr);
            $em->flush();
            return $this->redirectToRoute("admin_listUfrs");
        }
        return $this->render("RASPRaspBundle:Ufr:editUfr.html.twig", array('form' => $form->createView(), 'loggedInUser' => $loggedInUser));
    }

    public function ufrsAction()
    {
        // list UFRs
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $listUfr = $this->getDoctrine()->getRepository("RASPRaspBundle:Ufr")->findAll();
        return $this->render("RASPRaspBundle:Ufr:ufrs.html.twig", array('listUfr' => $listUfr, 'loggedInUser' => $loggedInUser));
    }

    public function showUfrAction($ufr_id)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $ufr = $this->getDoctrine()->getRepository("RASPRaspBundle:Ufr")->find($ufr_id);
        $em = $this->getDoctrine()->getManager();

        $listUsers = $em->getRepository("RASPRaspBundle:User")->findBy(array('ufr' => $ufr));
        $listRasp = $em->getRepository("RASPRaspBundle:Raspberry")->findBy(array('ufr' => $ufr));
        return $this->render("RASPRaspBundle:Ufr:ufr.html.twig", array('ufr' => $ufr, 'listUsers' => $listUsers, 'loggedInUser' => $loggedInUser, 'listRasp' => $listRasp));
    }

    public function editUfrAction($ufr_id, Request $request)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $ufr = $this->getDoctrine()->getRepository("RASPRaspBundle:Ufr")->find($ufr_id);

        $form = $this->createForm(UfrType::class, $ufr);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $ufr = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($ufr);
            $em->flush();
            return $this->redirectToRoute("admin_showUfr", array('ufr_id' => $ufr_id, 'loggedInUser' => $loggedInUser));
        }
        return $this->render("RASPRaspBundle:Ufr:editUfr.html.twig", array('form' => $form->createView(), 'loggedInUser' => $loggedInUser));
    }
}