<?php

namespace RASP\RComBundle\Controller;

use RASP\RaspBundle\Entity\Raspberry;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

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
        if (!$request == null){
            $uid = $request->get('uid');
            $rasp = $this->getDoctrine()->getRepository("RASPRaspBundle:Raspberry")->find($uid);
            $actionList = $this->getDoctrine()->getRepository("RComBundle:Action")->findBy(array("rasp" => $rasp));

            return new Response(100);
        }
    }
}
