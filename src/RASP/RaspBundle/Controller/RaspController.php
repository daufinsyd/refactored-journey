<?php
/**
 * Created by sydney_manjaro08/01/17
 */

namespace RASP\RaspBundle\Controller;
use RASP\RaspBundle\Form\User\RaspType;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

// Entities
use RASP\RaspBundle\Entity\Raspberry;

// Repo
use RASP\RaspBundle\Entity\RaspberryRepository;

/**
 * Class RaspController, handles rasp actions.
 *
 * This class stands for all the actions regarding the raspberries, that is, creation, deletion, and so forth.
 */
class RaspController extends Controller
{

    /**
     * Displays orphan raspberries.
     *
     * Recall that a raspberry is an orphan whenever it does not belong to an UFR.
     *
     * @return Response the page containing all the raspberries.
     */
    public function listOrphanRaspAction()
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $listRasp = $this->getDoctrine()->getRepository("RASPRaspBundle:Raspberry")->findBy(array("ufr" => NULL));

        return $this->render("RASPRaspBundle:Rasp:listRasp.html.twig", array('listRasp' => $listRasp, 'loggedInUser' => $loggedInUser));
    }

    /**
     * Shows information about a given rasp.
     *
     * @param int $rasp_id An integer to identify a raspberry.
     *
     * @return Response the required page.
     */
    public function showRaspAction($rasp_id)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $rasp = $this->getDoctrine()->getRepository("RASPRaspBundle:Raspberry")->find($rasp_id);


        return $this->render("RASPRaspBundle:Rasp:rasp.html.twig", array('rasp' => $rasp, 'loggedInUser' => $loggedInUser));
    }

    /**
     * Get all the rasps belonging to an UFR.
     *
     * Requires all the rasp belonging to the UFR denoted by $ufr_id. This function must not be mixed up with the
     * previous one, since the last one returned information about a given rasp, not the rasps of an UFR.
     *
     * @param int $ufr_id An integer to identify an UFR.
     *
     * @return Response The list of rasps attached to the UFR.
     */
    public function listRaspAction($ufr_id)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $ufr = $this->getDoctrine()->getRepository('RASPRaspBundle:Ufr')->find($ufr_id);
        $listRasp = $this->getDoctrine()->getRepository("RASPRaspBundle:Raspberry")->findBy(array("ufr" => $ufr));
        return $this->render("@RASPRasp/Rasp/listRasp.html.twig", array('listRasp' => $listRasp, 'loggedInUser' => $loggedInUser));
    }

    /**
     * Changes a rasp
     *
     * Update information of a rasp through a form passed as a parameter into a request. Notice that change information
     * about a rasp requires the current user to be an admin. Depending on the action, the function leads to the rasp
     * page on success, or loops back in the form on failure. Failure means wrong information into the form.
     *
     * @param int $rasp_id An integer to identify a raspberry.
     * @param Request $request The form information submitted.
     *
     * @return Response The resulting page.
     */
    public function editRaspAction($rasp_id, Request $request)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $listUfr = $this->getDoctrine()->getRepository('RASPRaspBundle:Ufr')->findAll();
        $rasp = $this->getDoctrine()->getRepository('RASPRaspBundle:Raspberry')->find($rasp_id);

        if ($this->isGranted('ROLE_ADMIN')) $isAdmin = True;
        else $isAdmin = False;

        $form = $this->createForm(RaspType::class, $rasp, array('listUfr' => $listUfr, 'isAdmin' => $isAdmin));
        $form->handleRequest($request);
        // helps to handle the form submission

        if($form->isSubmitted() && $form->isValid()){
            $rasp = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($rasp);
            $em->flush();

            return $this->render("RASPRaspBundle:Rasp:rasp.html.twig", array('rasp' => $rasp, 'rasp_id' => $rasp_id, 'loggedInUser' => $loggedInUser));
        }
        return $this->render("@RASPRasp/Rasp/editRasp.html.twig", array('form' => $form->createView(), 'loggedInUser' => $loggedInUser));
    }


    /**
     * Displays available functions for a raspberry.
     *
     * This function gives an access to all of the functions which can be applied to a raspberry. Those functions are
     * mainly restart, stop, update, get status, and so forth.
     *
     * @param int $rasp_id An integer to identify a raspberry.
     *
     * @return Response The page containing those functions.
     */
    public function listRaspActionsAction($rasp_id){
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $listRaspActions = $this->getDoctrine()->getRepository("RComBundle:RaspAction")->findBy(array('rasp' => $rasp_id));

        return $this->render("RASPRaspBundle:Rasp:listRaspActions.html.twig", array('loggedInUser' => $loggedInUser, 'listRaspActions' => $listRaspActions, 'rasp_id' => $rasp_id));


    }


    /**
     * Deletes a rasp.
     *
     * @param int $rasp_id An integer to identify a given rasp (to delete).
     *
     * @throws AccessDeniedException Thrown if the current user is not admin or deoens't belong to the same ufr as the
     * rasp's one.
     *
     * @return Response Returns to the main page.
     */
    public function deleteRaspAction($rasp_id){
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository("RASPRaspBundle:User")->find($loggedInUser->getId());

        $rasp = $this->getDoctrine()->getRepository("RASPRaspBundle:Raspberry")->find($rasp_id);

        if ($this->isGranted('ROLE_ADMIN') || $user->getUfr() == $rasp->getUfr()) {
            $em->remove($rasp);
            $em->flush();

            return $this->redirectToRoute("rasp_rasp_homepage");
        }
        else {
            throw new AccessDeniedException("Vous n'avez pas les bonnes permissions");
        }
    }
}