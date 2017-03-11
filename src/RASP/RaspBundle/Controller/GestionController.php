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
// TODO: add controls !

/**
 * Class GestionController, aims to handle user-oriented actions.
 *
 * Contains methods such as changing password, registration, view user parameters, and so forth. Details may be found
 * in methods description, nevertheless, the use of mailing in createUserAction should be upgraded whenever possible,
 * depending on FOSUser package's version.
 */
class GestionController extends Controller {


    /**
     * Aims to display user pages.
     *
     * @return Response A page displaying users.
     */
    public function usersAction(){
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser(); // get current user
        $listUser = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->findAll();

        // Get numbers of Admins
        $nbOfAdmins = count($this->getDoctrine()->getManager()->getRepository("RASPRaspBundle:User")->findByRoles('ROLE_ADMIN'));

        return $this->render('RASPRaspBundle:User/Gestion:users.html.twig', array("listUser" => $listUser,
            'loggedInUser' => $loggedInUser, 'nbOfAdmins' => $nbOfAdmins));
    }


    /**
     * Accesses a specific user page.
     *
     * The method will behave variously according to the rights granted to the given user, that is,
     * will redirect to the required page if the given id refers to an admin or the user itself, or throw
     * an exception otherwise.
     *
     * @param int $user_id An integer to identify a given user.
     *
     * @throws AccessDeniedException If the id does not correspond to an admin or the user required itself.
     *
     * @return Response The user (pointed out by $user_id) page.
     *
     */
    public function userAction($user_id)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->find($user_id); // find user in database through Doctrine

        // Get numbers of Admins
        $nbOfAdmins = count($this->getDoctrine()->getManager()->getRepository("RASPRaspBundle:User")->findByRoles('ROLE_ADMIN'));


        // if user is granted with super_admin status, then he can access the required page
        if ($user->getId() == $loggedInUser->getId() || $this->isGranted('ROLE_ADMIN')) {
            return $this->render("RASPRaspBundle:User/Gestion:user.html.twig", array("user" => $user, 'loggedInUser' => $loggedInUser, 'nbOfAdmins' => $nbOfAdmins));

        } else {
            throw new AccessDeniedException("Vous n'avez pas les bonnes permissions");
        }
    }


    /**
     * Changes password.
     *
     * Tries to change the password of a given user, through a form passed as a request. Actually redirects either to
     * the user page if success, loops on the form otherwise.
     *
     * @param int $user_id An integer to identify a given user.
     * @param Request $request The form containing information about password modification.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response The page to be redirected on.
     */
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


    /**
     * Allows an admin user to modify a given user's information.
     *
     * The modifications rely on a form to be submitted. Notice that a user must be granted admin rights or the user
     * with the id $user_id itself to access such a feature. An exception will be thrown whenever it is not the case.
     *
     * @param int $user_id An integer to identify a given user.
     * @param Request $request The form containing modifications.
     *
     * @throws AccessDeniedException If $user_id does neither match the current user nor an admin one.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response The page to be redirected on.
     */
    public function editUserAction($user_id, Request $request)
    {

        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->find($user_id);
        $userId = $user->getId();

        if ($userId == $loggedInUser->getId() || $this->isGranted('ROLE_ADMIN')) {
            $listUfr = $this->getDoctrine()->getRepository("RASPRaspBundle:Ufr")->findAll();
            $isSuperAdmin = $user->hasRole('ROLE_ADMIN');

            $form = $this->createForm(UserType::class, $user, array('listUfr' => $listUfr, 'super_admin' => $isSuperAdmin));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                if ($this->isGranted('ROLE_ADMIN')){
                    /* If the user who validate the form is admin, he could have changed UFR or ADMIN
                     * let check it
                     */
                    if ($form->get('super_admin')->getData() == true) {
                        if ( ! $user->hasRole('ROLE_ADMIN') ) {
                            /* If user is not admin but we want to change it,
                             * add admins roles
                             */
                            $user->addRole('ROLE_ADMIN');  // for web pages
                            $user->addRole('ROLE_SUPER_ADMIN');  // for security.yml
                        }
                    }
                    else {
                        if( $user->hasRole('ROLE_ADMIN') ){
                            /* If user is admin but we want to change it,
                             * remove admins roles
                             */
                            $user->removeRole('ROLE_ADMIN');
                            $user->removeRole('ROLE_SUPER_ADMIN');
                        }
                    }
                }

                $user = $form->getData();

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('admin_showUser', array('user_id' => $user_id, 'loggedInUser' => $loggedInUser));
            }
            $nbOfAdmins = count($this->getDoctrine()->getRepository('RASPRaspBundle:User')->findByRoles('ROLE_ADMIN'));

            return $this->render('RASPRaspBundle:User/Gestion:editUser.html.twig', array('form' => $form->createView(),
                'loggedInUser' => $loggedInUser, 'nbOfAdmins' => $nbOfAdmins, 'userId' => $userId));
        }
        else throw new AccessDeniedException("Vous n'avez pas les bonnes permissions.");
    }


    /**
     * Creates an user.
     *
     * Feature for admin only. Creates an user through a form to be filled, and sent so. Notice that trying to access
     * the page would result in an exception if the current user has not the convenient rights. This method may send
     * a confirmation mail to the new user, so that the so said user must activate his account through a link. Using
     * sendConfirmationEmailMessage is probably not the most elegant way to call the mailer functionality of
     * FOSUser, but according to RegistrationController (present in vendor/friendsofsymfony/Controller), there is no
     * mailing function about register.
     * Troubleshooting this issue could be done by trying to mix up the forms given by FOSUser with the needs of ours,
     * even though the form used here has already been declared to be the default FOSUser one.
     *
     * @param Request $request Contains the form with new user credentials.
     *
     * @throws AccessDeniedException If the current user is not an admin.
     *
     * @return Response The user page in case of success, loop on the form otherwise.
     */
    public function createUserAction(Request $request)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();

        // adding an user requires admin rights
        if ($this->isGranted('ROLE_ADMIN')) {

            $user = new User(); // create an user
            $user->setPlainPassword(random_bytes(10)); // with a random password
            $user->setConfirmationToken(random_bytes(10));

            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user = $form->getData();

                if ($form->get('super_admin')->getData() == true){
                    /* Gant user ADMIN roles if super_admin is checked */
                    $user->addRole('ROLE_ADMIN');  // for web pages
                    $user->addRole('ROLE_SUPER_ADMIN');  // for security.yml
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                // must send an email here
                $this->get('fos_user.mailer')->sendConfirmationEmailMessage($user);
                return $this->redirectToRoute('admin_userSuccess', array('user_id' => $user->getId(), 'loggedInUser' => $loggedInUser));
            }

            $nbOfAdmins = count($this->getDoctrine()->getRepository('RASPRaspBundle:User')->findByRoles('ROLE_ADMIN'));
            // Same template as editUser, except for userId. userId is set to -1 to allow user creation, it will fail
            // otherwise.
            return $this->render('RASPRaspBundle:User/Gestion:editUser.html.twig', array('form' => $form->createView(), 'loggedInUser' => $loggedInUser,
                'nbOfAdmins' => $nbOfAdmins, 'userId' => -1));
        }
        else throw new AccessDeniedException("Vous n'avez pas les bonnes permissions.");
    }


    /**
     * Deletes an user.
     *
     * @param int $user_id An integer to identify a given user (to delete).
     *
     * @throws AccessDeniedException Thrown if the current user is not admin.
     *
     * @return Response Returns to the users list.
     */
    public function deleteUserAction($user_id)
    {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository("RASPRaspBundle:User")->find($user_id);

        // Get numbers of Admins
        $nbOfAdmins = count($this->getDoctrine()->getManager()->getRepository("RASPRaspBundle:User")->findByRoles('ROLE_ADMIN'));

        if ($this->isGranted('ROLE_ADMIN')) {
            if ($user && !($user->getId() == $loggedInUser->getId())) {
                $em->remove($user);
                $em->flush();

            }

            $listUser = $em->getRepository("RASPRaspBundle:User")->findAll();
            return $this->render('RASPRaspBundle:User/Gestion:users.html.twig', array("listUser" => $listUser,
                'loggedInUser' => $loggedInUser, 'nbOfAdmins' => $nbOfAdmins));

        } else {
            throw new AccessDeniedException("Vous n'avez pas les bonnes permissions.");

        }

    }


    /**
     * Success resulting page.
     *
     * @param int $user_id An integer to identify an user.
     *
     * @return Response A page resulting from a successful action.
     */
    public function userSuccessAction($user_id) {
        $loggedInUser = $this->get('security.token_storage')->getToken()->getUser();
        $user = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->find($user_id);
        return $this->render("RASPRaspBundle:User/Gestion:user.html.twig", array("user" => $user, 'loggedInUser' => $loggedInUser));
    }


    /**
     * Enables an user.
     *
     * Given an id, an admin (an admin only) user will be able to toggle or not an user. An exception will be thrown
     * if the current user is not admin.
     *
     * @param int $user_id An integer to identify an user to enable/disable.
     *
     * @throws AccessDeniedException Thrown if the current user is not admin.
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse Reroutes to success page previously defined.
     */
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
