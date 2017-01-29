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




/* class UfrController -------------------------------------------------------------------------------------------------
 * Attributes :
 *
 * Methods :
 *    public createUfrAction(Request)
 *    public ufrsAction()
 *    public showUfrAction(int)
 *    public editUfrAction(int, request)
 *
 * Description :
 *    Aims to handle Ufr entities gesture, that is, edit/create/show ufr.
 *
--------------------------------------------------------------------------------------------------------------------- */

class UfrController extends Controller
{

    /* createUfrAction -------------------------------------------------------------------------------------------------
     * Input :
     *   Request $request --> result got from page (e.g a form)
     * Output :
     *   Redirection to a page depending on input.
     *
     * Desc :
     *   Tries to create a new ufr. Form is valid implies redirection to
     *   ufr main page, to ufr edition page otherwise.
     * -------------------------------------------------------------------------------------------------------------- */
    public function createUfrAction(Request $request)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $ufr = new Ufr();

        // creating a form to be completed by the user, and handle its completion
        $form = $this->createForm(UfrType::class, $ufr);
        $form->handleRequest($request);

        // if the form is correct, it is added to database through Doctrine
        if($form->isSubmitted() && $form->isValid()){
            $ufr = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($ufr); // persist allow Doctrine to handle the entity
            $em->flush(); // save the entity into the database
            return $this->redirectToRoute("admin_listUfrs");
        }
        return $this->render("RASPRaspBundle:Ufr:editUfr.html.twig", array('form' => $form->createView(), 'loggedInUser' => $loggedInUser));
    }



    /* ufrsAction ------------------------------------------------------------------------------------------------------
     * Input :
     * Output :
     *   Redirection to ufr main page (listing ufr).
     *
     * Desc :
     *   Get the list of all available ufr.
     * -------------------------------------------------------------------------------------------------------------- */
    public function ufrsAction()
    {
        // list UFRs
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $listUfr = $this->getDoctrine()->getRepository("RASPRaspBundle:Ufr")->findAll();
        return $this->render("RASPRaspBundle:Ufr:ufrs.html.twig", array('listUfr' => $listUfr, 'loggedInUser' => $loggedInUser));
    }



   /* showUfrAction ----------------------------------------------------------------------------------------------------
    * Input :
    *   int $ufr_id --> ufr identifier
    * Output :
    *   Redirection to the given ufr page.
    *
    * Desc :
    *   Get available information about the selected ufr.
    * --------------------------------------------------------------------------------------------------------------- */
    public function showUfrAction($ufr_id)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $ufr = $this->getDoctrine()->getRepository("RASPRaspBundle:Ufr")->find($ufr_id);
        $em = $this->getDoctrine()->getManager();

        // get the users and rasps related to the ufr $ufr_id
        $listUsers = $em->getRepository("RASPRaspBundle:User")->findBy(array('ufr' => $ufr));
        $listRasp = $em->getRepository("RASPRaspBundle:Raspberry")->findBy(array('ufr' => $ufr));

        // send the view containing required information
        return $this->render("RASPRaspBundle:Ufr:ufr.html.twig", array('ufr' => $ufr, 'listUsers' => $listUsers, 'loggedInUser' => $loggedInUser, 'listRasp' => $listRasp));
    }


    /* editUfrAction ---------------------------------------------------------------------------------------------------
     * Input :
     *   int     $ufr_id  --> ufr identifier
     *   Request $request --> information got from the page (e.g a form)
     * Output :
     *   Redirection to a page depending on input.
     *
     * Desc :
     *   If valid form then redirection to the modified ufr page. Loop on the edition page otherwise.
     * -------------------------------------------------------------------------------------------------------------- */
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