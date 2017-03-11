<?php
/**
 *
 */
namespace RASP\RaspBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class DefaultController, handles login pages
 */
class DefaultController extends Controller
{

    /**
     * Handles login page.
     *
     * Response to "/" route, if there exists a session, the user will be redirected on his page, to "/login"
     * otherwise.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response Either a
     * response to user page, or redirected to login.
     */
    public function indexAction()
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $securityContext = $this->get('security.authorization_checker');

        $nbOfAdmins = count($this->getDoctrine()->getRepository("RASPRaspBundle:User")->findByRoles("ROLE_ADMIN"));

        if ($securityContext->isGranted('ROLE_USER') or $securityContext->isGranted('ROLE_ADMIN')) {
            //return $this->render('RASPRaspBundle:Default:index.html.twig', array('loggedInUser' => $loggedInUser));
            return $this->render("RASPRaspBundle:User/Gestion:user.html.twig", array("user" => $loggedInUser,
                'loggedInUser' => $loggedInUser, 'nbOfAdmins' => $nbOfAdmins));

        } else {
            return $this->redirect("/login");

        }

    }

    /**
     * Get admin page
     *
     * @return \Symfony\Component\HttpFoundation\Response The response containing the admin page.
     */
    public function adminIndexAction()
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('RASPRaspBundle::admin.html.twig', array('loggedInUser' => $loggedInUser));
    }
}