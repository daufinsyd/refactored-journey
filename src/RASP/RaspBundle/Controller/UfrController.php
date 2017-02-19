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


/**
 * Class UfrController, aims to handle ufr actions
 *
 * This class aims to provide functions to handle the UFR groups, with features such as editing an UFR, deletion,
 * update, and so on.
 */
class UfrController extends Controller
{

    /**
     * Creates an UFR.
     *
     * Depending on the form submitted, the user will be redirected to the UFR list in case of success, and will
     * loop on the form otherwise.
     *
     * @param Request $request the form to create an UFR.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response The resulting page.
     */
    public function createUfrAction(Request $request)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $ufr = new Ufr();

        // creating a form to be completed by the user, and handle its completion
        $form = $this->createForm(UfrType::class, $ufr);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $ufr = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($ufr); // persist allow Doctrine to handle the entity
            $em->flush(); // save the entity into the database
            return $this->redirectToRoute("admin_listUfrs");
        }
        return $this->render("RASPRaspBundle:Ufr:editUfr.html.twig", array('form' => $form->createView(), 'loggedInUser' => $loggedInUser));
    }


    /**
     * Lists all UFRs.
     *
     * @return Response The page containing a list of all UFRs.
     */
    public function ufrsAction()
    {
        // list UFRs
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $listUfr = $this->getDoctrine()->getRepository("RASPRaspBundle:Ufr")->findAll();
        return $this->render("RASPRaspBundle:Ufr:ufrs.html.twig", array('listUfr' => $listUfr, 'loggedInUser' => $loggedInUser));
    }


    /**
     * Show a specific UFR.
     *
     * @param int $ufr_id An integer to identify an UFR.
     *
     * @return Response The resulting page.
     */
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


    /**
     * Changes an UFR.
     *
     * @param int $ufr_id An integer to identify an UFR.
     * @param Request $request The form containing modifications.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response The resulting page.
     */
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
