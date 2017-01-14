<?php

namespace RASP\RComBundle\Controller;

// Entities
use RASP\RaspBundle\Entity\User;
use RASP\RaspBundle\Entity\Raspberry;
use RASP\RComBundle\Entity\RaspAction;

// Repository
use RASP\RComBundle\Repository\RaspActionRepository;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

// Serialization
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RComBundle:Default:index.html.twig');
    }

    public function whatsupAction(Request $request=null){
        if ($request != null) {
            $content = $request->getContent();

            $id = $request->get('id');
            $status = $request->get('status');
            $info = $request->get('info');
            $shortLog = $request->get('shortLog');

            $fs = new Filesystem();
            $data = array("id" => $id, "info" => $info, "status" => $status, "shortLog" => $shortLog);
            $json = json_encode($data);
            $fs->dumpFile('/home/sydney_manjaro/tmp.json', $json);
        }
        return new Response(200);
    }

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

    public function rebootPlzAction(Request $request, $rasp_id){
        if ($request != null) {
            $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
            $rasp = $this->getDoctrine()->getRepository('RASPRaspBundle:Raspberry')->find($rasp_id);

            if($rasp->getUfr() == $loggedInUser->getUfr()){
                $newAction = new RaspAction();
                $newAction->setCodeCmd(1);
                $newAction->setCmd(10);
                $newAction->setRasp($rasp);

                $em = $this->getDoctrine()->getManager();
                $em->persist($newAction);
                $em->flush();

                return $this->redirectToRoute("rasp_listActions", array('rasp_id' => $rasp_id));
            }
        }
    }
}
