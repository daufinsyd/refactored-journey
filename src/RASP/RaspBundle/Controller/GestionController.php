<?php
/**
 * Created by PhpStorm.
 * User: Simon
 * Date: 02/01/2017
 * Time: 14:54
 * Path : RCCF/src/LOG/LoginBundle/Controller/GestionController.php
 */

namespace RASP\RaspBundle\Controller;
use Doctrine\ORM\Mapping\Id;
use RASP\RaspBundle\Form\User\UserPasswdType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// for absolute path
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
// for JsonsResponse
use Symfony\Component\HttpFoundation\JsonResponse;

// for types
use RASP\RaspBundle\Form\User\UserType;
use RASP\RaspBundle\Form\User\UserPasswordType;
// Entities
use RASP\RaspBundle\Entity\User;
// for user repo
use RASP\RaspBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;


// envoyer lien de validation par mail

/* class GestionController ---------------------------------------------------------------------------------------------
 * Attributes :
 *
 * Methods :
 *     public usersAction()
 *     public userAction(int)  (beware of the difference user / users)
 *     public function userPasswordAction(int, Request)
 *     public function editUserAction(int, Request)
 *     public function createUserAction(Request)
 *     public function deleteUserAction(int)
 *     public function userSuccessAction(int)
 *     public function toggleUserAction(int)
 *
 * Description :
 *     Aims to handle user gesture, that is creation/display/deletion/update and so forth.
 *
--------------------------------------------------------------------------------------------------------------------- */

class GestionController extends Controller {


    /* usersAction -------------------------------------------------------------------------------------------------
     * Input :
     * Output :
     *   Redirection to a listing page.
     *
     * Desc :
     *   display the list of all known users with information such as their status, name, and so on.
     * -------------------------------------------------------------------------------------------------------------- */
    public function usersAction(){
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser(); // get current user
        $listUser = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->findAll();
        return $this->render('RASPRaspBundle:User/Gestion:users.html.twig', array("listUser" => $listUser, 'loggedInUser' => $loggedInUser));
    }



    /* userAction ------------------------------------------------------------------------------------------------------
     * Input :
     *   int $user_id --> id of an user we would like to have infos on.
     * Output :
     *   Redirection to a page depending on input.
     *
     * Desc :
     *   Aims to reroute on a specific user page. Depending on the access of the logged user, the function will
     *   either redirect  on the required page, or throw an AccessDeniedException exception.
     * -------------------------------------------------------------------------------------------------------------- */
    public function userAction($user_id)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->find($user_id); // find user in database through Doctrine

        // if user is granted with super_admin status, then he can access the required page
        if ($user->getId() == $loggedInUser->getId() || $this->isGranted('ROLE_ADMIN')) {
            return $this->render("RASPRaspBundle:User/Gestion:user.html.twig", array("user" => $user, 'loggedInUser' => $loggedInUser));

        } else {
            throw new AccessDeniedException("Vous n'avez pas les bonnes permissions");
        }
    }



    /* userPasswordAction ----------------------------------------------------------------------------------------------
     * Input :
     *   Request $request --> result got from page (e.g a form)
     *   int     $user_id --> the id the request refers to
     * Output :
     *   Redirection to a page depending on input.
     *
     * Desc :
     *   Helps to change an user password. If data are valid, the new pass is submitted, reroute to user gesture
     *   otherwise.
     * -------------------------------------------------------------------------------------------------------------- */
    public function userPasswordAction($user_id, Request $request)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->find($user_id);

        $form = $this->createForm(UserPasswdType::class, $user);
        $form->handleRequest($request);

        // if the submitted form is valid
        if($form->isSubmitted() && $form->isValid()) {
            // get the new pass and update
            $user->setPlainPassword($form->get('password')->getData());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            return $this->redirectToRoute("admin_showUser", array('user_id' => $user_id, 'loggedInUser' => $loggedInUser));
        }
        return $this->render('RASPRaspBundle:User/Gestion:userPassword.html.twig', array('form' => $form->createView(), 'user' => $user, 'loggedInUser' => $loggedInUser));
    }



    /* editUserAction -------------------------------------------------------------------------------------------------
     * Input :
     *   Request $request --> result got from page (e.g a form)
     *   int     $user_id --> the id the request refers to
     * Output :
     *   Redirection to a page depending on input.
     *
     * Desc :
     *   Helps to edit an user. Admin rights are required.
     * -------------------------------------------------------------------------------------------------------------- */
    public function editUserAction($user_id, Request $request)
    {

        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->find($user_id);

        if ($user->getId() == $loggedInUser->getId() || $this->isGranted('ROLE_ADMIN')) {
            $listUfr = $this->getDoctrine()->getRepository("RASPRaspBundle:Ufr")->findAll();

            $form = $this->createForm(UserType::class, $user, array('listUfr' => $listUfr));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $user = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('admin_showUser', array('user_id' => $user_id, 'loggedInUser' => $loggedInUser));
            }

            return $this->render('RASPRaspBundle:User/Gestion:editUser.html.twig', array('form' => $form->createView(), 'loggedInUser' => $loggedInUser));
        }
        else throw new AccessDeniedException("Vous n'avez pas les bonnes permissions.");
    }



    /* createUserAction ------------------------------------------------------------------------------------------------
     * Input :
     *   Request $request --> result got from page (e.g a form)
     * Output :
     *   Redirection to a page depending on input. Write a new user into the database.
     *
     * Desc :
     *   Aims to create a new user through a form passed as an argument. Whenever the form is valid, the user is
     *   created, redirection to the user edition page otherwise. Note : only an admin can create an user,
     * -------------------------------------------------------------------------------------------------------------- */
    public function createUserAction(Request $request)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();

        // adding an user requires admin rights
        if ($this->isGranted('ROLE_ADMIN')) {

            $user = new User(); // create an user
            $user->setPlainPassword(random_bytes(10)); // with a random password
            $user->setConfirmationToken('azerty');

            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                // must send an email here
                 $this->get('fos_user.mailer')->sendConfirmationEmailMessage($user);
                // return $this->redirectToRoute('admin_userSuccess', array('user_id' => $user->getId(), 'loggedInUser' => $loggedInUser));
            }
            // Same template as editUser
            return $this->render('RASPRaspBundle:User/Gestion:editUser.html.twig', array('form' => $form->createView(), 'loggedInUser' => $loggedInUser));
        }
        else throw new AccessDeniedException("Vous n'avez pas les bonnes permissions.");
    }



    /* deleteUserAction -------------------------------------------------------------------------------------------------
     * Input :
     *  int $user_id --> the user's id we want to delete.
     * Output :
     *   Redirection to a page depending on input.
     *
     * Desc :
     *   Tries to delete a given user.
     * -------------------------------------------------------------------------------------------------------------- */
    public function deleteUserAction($user_id)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository("RASPRaspBundle:User")->find($user_id);

        if ($this->isGranted('ROLE_ADMIN')) {
            if ($user && !($user->getId() == $loggedInUser->getId())) {
                $em->remove($user);
                $em->flush();

            }

            $listUser = $em->getRepository("RASPRaspBundle:User")->findAll();
            return $this->render('RASPRaspBundle:User/Gestion:users.html.twig', array("listUser" => $listUser, 'loggedInUser' => $loggedInUser));

        } else {
            throw new AccessDeniedException("Vous n'avez pas les bonnes permissions.");

        }

    }



    /* userSuccessAction -----------------------------------------------------------------------------------------------
     * Input :
     *   int $user_id --> the id the action refers to
     * Output :
     *   user success page.
     *
     * Desc :
     *   Usually used when a successful action on the given user has been done.
     * -------------------------------------------------------------------------------------------------------------- */
    public function userSuccessAction($user_id) {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->find($user_id);
        return $this->render("RASPRaspBundle:User/Gestion:user.html.twig", array("user" => $user, 'loggedInUser' => $loggedInUser));
    }



    /* toggleUserAction -------------------------------------------------------------------------------------------------
     * Input :
     *   int $user_id --> the id the action refers to
     * Output :
     *   Redirection to a page depending on input.
     *
     * Desc :
     *   Enable or disable an user.
     * -------------------------------------------------------------------------------------------------------------- */
    public function toggleUserAction($user_id)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $user = $this->getDoctrine()->getRepository('RASPRaspBundle:User')->find($user_id);
            $em = $this->getDoctrine()->getManager();

            if ($user->isEnabled()) {
                $user->setEnabled(false);

            } else {
                $user->setEnabled(true);
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_userSuccess', array('user_id' => $user->getId(), 'loggedInUser' => $loggedInUser));
        }
        else throw new AccessDeniedException("Vous n'avez pas les bonnes permissions.");
    }
}
