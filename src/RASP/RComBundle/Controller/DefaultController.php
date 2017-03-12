<?php

namespace RASP\RComBundle\Controller;

// Entities
use RASP\RaspBundle\Entity\Raspberry;
use RASP\RComBundle\Entity\RaspAction;

// Repository
use RASP\RComBundle\Repository\RaspActionRepository;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Serialization
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class DefaultController
 *
 * This controller aims to process the connection between the raspberries and the server with actions such as
 * get information, get pending actions, and so forth.
 */
class DefaultController extends Controller
{
    /**
     * Aims to display main communication page.
     *
     * @return Response The required page.
     */
    public function indexAction()
    {
        return $this->render('RComBundle:Default:index.html.twig');
    }


    /**
     * Say hello to the server and update info.
     *
     * This function is used whenever a rasp needs to identify itself to the server. It is also useful for an update
     * of informations such as the rasp status. If the server does not recognize the sender of the request, it tries
     * to add it as a new raspberry. Otherwise the server will find it by looking for the uuid which is unique right
     * into the Raspberry table.
     *
     * @param Request|null $request the request coming from a born.
     *
     * @return Response the response to be sent to the born.
     */
    public function whatsupAction(Request $request = null){
        if ($request != null) {
            $content = $request->getContent();

            // Get data
            $uuid = $request->get('id');
            $status = $request->get('status');
            $info = $request->get('info');
            $shortLog = $request->get('shortLog');

            // Get or create the rasp
            $borne = $this->getDoctrine()->getRepository("RASPRaspBundle:Raspberry")->findBy(array("uuid" => $uuid));
            if(!$borne) {
                // If doesn't already exist
                $borne = new Raspberry();
                $borne->setUuid($uuid);
            }
            else $borne = $borne[0];  // If already exist, then $born is an array (cf up), but uuid is unique so we get the unique element of the array
            // Set / update data
            $borne->setStatus($status);
            $borne->setInfo($info);
            $borne->setShortLog($shortLog);

            // Save data
            $em = $this->getDoctrine()->getManager();
            $em->persist($borne);
            $em->flush();
        }
        return new Response(200);
    }

    /**
     * Retrieve list of pending actions.
     *
     * This function send to an asking rasp the list of pending actions it has to fulfill. The response is sent as
     * a JSON response, to be handled by a python program on the client-side (rasp-side). The JSON contains all the
     * actions serialized from the action database.
     *
     * @param Request|null $request A request from a machine waiting for its action to perform.
     *
     * @return Response The response containing the actions.
     */
    public function imboredAction(Request $request = null){

        if ($request != null){
            // Get actionList
            $uuid = $request->get('uuid');
            $rasp = $this->getDoctrine()->getRepository("RASPRaspBundle:Raspberry")->findBy(array("uuid" => $uuid));
            $actionList = $this->getDoctrine()->getRepository("RComBundle:RaspAction")->findBy(array("rasp" => $rasp));

            // Serialize actionList with only required fields
            $normalizer = new ObjectNormalizer();
            $normalizer->setIgnoredAttributes(array('ufr', 'rasp'));
            $encoder = new JsonEncoder();
            $serializer = new Serializer(array($normalizer), array($encoder));
            $serializer->serialize($actionList, 'json');
            $json = $serializer->serialize($actionList, 'json');

            return new Response($json);
        }
    }

    /**
     * User delete an action of an specific rasp
     *
     * As a rasp belongs to a particular ufr, just an user of the same ufr can access to actions such as delete an
     * action. As a consequence, an user of a different ufr may be thrown with an AccessDeniedException. On the
     * other case, the deletion can be accessed.
     *
     * @param Request $request the request sent to get the page.
     * @param $rasp_id An integer to identify a raspberry to modify.
     * @param $action_id An integer to identify the action to be deleted.
     *
     * @throws AccessDeniedException Occurs whenever an user does not belong to another ufr.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteActionAction(Request $request, $rasp_id, $action_id)
    {
        if ($request != null) {
            $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
            $rasp = $this->getDoctrine()->getRepository('RASPRaspBundle:Raspberry')->find($rasp_id);

            if ($rasp->getUfr() == $loggedInUser->getUfr()) {
                $action = $this->getDoctrine()->getRepository("RComBundle:RaspAction")->find($action_id);
                $em = $this->getDoctrine()->getManager();
                $em->remove($action);
                $em->flush();

                return $this->redirectToRoute("rasp_listActions", array('rasp_id' => $rasp_id));
            }

            throw $this->createAccessDeniedException('Vous n\'avez pas les droits appropriés pour effectuer une telle action!');
        }
    }

    /**
     * Send a message to a raspberry to reboot.
     *
     * This function will throw an exception whenever an user from a wrong ufr tries to access it.
     *
     * @param Request $request the request of the page.
     * @param $rasp_id An integer to identify the id to reboot.
     *
     * @throws AccessDeniedException An user may not be able to access this page if it does not belong to the rasp' ufr.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse  redirect to the rasp page.
     */
    public function rebootPlzAction(Request $request, $rasp_id){
        return $this->GenericFunction($request, $rasp_id, 1, 10);
    }

    public function upgradePlzAction(Request $request, $rasp_id){
        return $this->GenericFunction($request, $rasp_id, 1, 2);
    }

    public function updatePlzAction(Request $request, $rasp_id){
        return $this->GenericFunction($request, $rasp_id, 2, 3);
    }

    public function personalPlzAction(Request $request, $rasp_id){
        return $this->GenericFunction($request, $rasp_id, 1, 10);
    }

    public function GenericFunction(Request $request, $rasp_id, $codeCmd, $Cmd){
        if ($request != null) {
            $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
            $rasp = $this->getDoctrine()->getRepository('RASPRaspBundle:Raspberry')->find($rasp_id);

            if($rasp->getUfr() == $loggedInUser->getUfr()){
                $newAction = new RaspAction();
                $newAction->setCodeCmd($codeCmd);
                $newAction->setCmd($Cmd);
                $newAction->setRasp($rasp);

                $em = $this->getDoctrine()->getManager();
                $em->persist($newAction);
                $em->flush();

                return $this->redirectToRoute("rasp_listActions", array('rasp_id' => $rasp_id));
            }

            throw $this->createAccessDeniedException('Vous n\'avez pas les droits appropriés pour effectuer une telle action!');
        }

    }
}