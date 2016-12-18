<?php

namespace OC\PlatformBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

#for absolute path 
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#to use JsonsResponse
use Symfony\Component\HttpFoundation\JsonResponse;


class AdvertController extends Controller
{
    /*public function indexAction()
    {
	    //return new Response("Notre propre Hello World!");
	    //Avec Symfony 3 on n'utilise plus get('templating')
        $content = $this->render('OCPlatformBundle:Advert:index.html.twig');
	return new Response($content);
    }*/
    public function viewAction($id, Request $request)
    {
        $tag = $request->query->get('tag');  // standard GET parameter + don't need to isset
        /*
         * Other:
         * GET - query
         * POST - request
         * COOKIE - cookies
         * SERVER - server
         * $_SERVER[HTTP*] - headers
         * request->attributes == para (ie: $id)
         */

        $request->isMethod('GET') == true ? $requestType = "GET" : $requestType = "POST";
        $session = $request->getSession();
        $userId = $session->get('userId');
        $session->set('userId', 56);

		//return new Response("Affichage de l'annonce d'id: " . $id . "<br/>Avec le tag: " . $tag . "<br/>La requete est de type: " . $requestType);
        //return $this->get('templating')->renderResponse('OCPlatformBundle:Advert:testView.html.twig', array('tag' => $tag, 'id' => $id, 'requestType' => $requestType, 'userId' => $userId));
	    // render and renderResponse are the same: $this->render();




        $advert = array(
            'title'   => 'Recherche développpeur Symfony2',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
        );

        return $this->render('OCPlatformBundle:Advert:view.html.twig', array(
            'advert' => $advert,
            'tag' => $tag, 'id' => $id, 'requestType' => $requestType, 'userId' => $userId));


    }
	
	public function viewSlugAction($slug, $year, $format)
	{
		return new Response("Vous voulez l'annonce : slug: " . $slug . " d'année " . $year . " au format " . $format);
	}
	public function indexAction()
	{
		$url = $this->get('router')->generate('oc_platform_view', array('id' => 4));
		$absUrl = $this->generateUrl('oc_platform_view', array('id' => 4), UrlGeneratorInterface::ABSOLUTE_URL);
		//return new Response("L'url de l'annonce d'id 4 est: " . $url . "<br/>Son chemin absolu: " . $absUrl);


        // Notre liste d'annonce en dur
        $listAdverts = array(
            array(
                'title'   => 'Recherche développpeur Symfony',
                'id'      => 1,
                'author'  => 'Alexandre',
                'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Mission de webmaster',
                'id'      => 2,
                'author'  => 'Hugo',
                'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
                'date'    => new \Datetime()),
            array(
                'title'   => 'Offre de stage webdesigner',
                'id'      => 3,
                'author'  => 'Mathieu',
                'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
                'date'    => new \Datetime())
                );
        
        return $this->render('OCPlatformBundle:Advert:index.html.twig', array(
            'listAdverts' => $listAdverts
        ));
	}

    public function error_notFoundAction()
    {
        $response = new Response();
        $response->setContent("Erreur 404 pour le bundle Platform");
        $response->setStatusCode($response::HTTP_NOT_FOUND);  // or 404
        return $response;
    }

    public function redirectPlzAction()
    {
        //$url = $this->get('router')->generate('oc_platform_home');
        //return $this->redirect($url);
        return $this->redirectToRoute('oc_platform_home');
    }

    public function getJsonDataAction()
    {
        //$jsonReponse = new Response(json_encode(array('id' => 1, 'slug' => "bjr les amis!")));
        //$jsonReponse->headers->set('Content-type', 'application/json');
        //return $jsonReponse;
        // Ha ben efait comme on est des gros flemmards non
        $jsonResponse = new JsonResponse();
        $jsonResponse->setData(array('id' => 1, 'slug' => "bjr les amis!"));
        return $jsonResponse;
    }

    public function addAction(Request $request)
    {
        // In symfony 3 syntax changed
        // see https://symfony.com/doc/current/controller.html#flash-messages
        $this->addFlash(
            'notice',
            'success!'
        );

        return $this->redirectToRoute('oc_platform_view', array('id' => 4));
    }

    public function menuAction(){
        // On fixe en dur une liste ici, bien entendu par la suite
        // on la récupérera depuis la BDD !
        $listAd = array(
            array('id' => 2, 'title' => 'Recherche développeur Python'),
            array('id' => 3, 'title' => 'Mission de webmaster'),
            array('id' => 7, 'title' => 'Offre de stage 2e année webdesigner')
        );

        return $this->render('OCPlatformBundle:Advert:menu.html.twig', array(
            // Tout l'intérêt est ici : le contrôleur passe
            // les variables nécessaires au template !
            'listAd' => $listAd
        ));
    }
    public function editAction($id, Request $request)
    {
        $advert = array(
            'title'   => 'Recherche développpeur Symfony',
            'id'      => $id,
            'author'  => 'Alexandre',
            'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
            'date'    => new \Datetime()
        );

        return $this->render('OCPlatformBundle:Advert:edit.html.twig', array(
            'advert' => $advert
        ));
    }

}


