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

class GestionController extends Controller {
    // Actions for RCCF admin for users

    public function profileAction(){
        return new Response("hello world !");
    }

    public function usersAction(){
        // List users
        $listUser = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->findAll();
        return $this->render('RASPRaspBundle:User/Gestion:users.html.twig', array("listUser" => $listUser));
    }

    public function userAction($user_id)
    {
        // Show specific user
        $user = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->find($user_id);
        return $this->render("RASPRaspBundle:User/Gestion:user.html.twig", array("user" => $user));
    }

    public function userPasswordAction($user_id, Request $request)
    {
        $user = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->find($user_id);

        $form = $this->createForm(UserPasswdType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //$user = $form->getData();
            $user->setPlainPassword($form->get('password')->getData());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute("admin_showUser", array('user_id' => $user_id));
        }
        return $this->render('RASPRaspBundle:User/Gestion:userPassword.html.twig', array('form' => $form->createView(), 'user' => $user));
    }

    public function editUserAction($user_id, Request $request)
    {
        // Show specific user
        $user = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->find($user_id);

        $listUfr = $this->getDoctrine()->getRepository("RASPRaspBundle:Ufr")->findAll();

        $form = $this->createForm(UserType::class, $user, array('listUfr' => $listUfr));
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_showUser', array('user_id' => $user_id));
        }

        return $this->render('RASPRaspBundle:User/Gestion:editUser.html.twig', array('form' => $form->createView()));
    }

    public function createUserAction(Request $request)
    {
        // Create a new user
        $user = new User();
        // Auto generate a new password (should be set when on userfirst cinnexion via email link)
        $user->setPlainPassword(random_bytes(10));

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('admin_userSuccess', array('user_id' => $user->getId()));
        }
        // Same template as editUser
        return $this->render('RASPRaspBundle:User/Gestion:editUser.html.twig', array('form' => $form->createView()));
    }

    public function userSuccessAction($user_id) {
        $user = $this->getDoctrine()->getRepository("RASPRaspBundle:User")->find($user_id);
        return $this->render("RASPRaspBundle:User/Gestion:user.html.twig", array("user" => $user));
    }

    public function toggleUserAction($user_id){
        // TODO: implement ROLE
        $user = $this->getDoctrine()->getRepository('RASPRaspBundle:User')->find($user_id);
        $em = $this->getDoctrine()->getManager();
        if ($user->isEnabled()) {
            $user->setEnabled(false);
        }
        else {
            $user->setEnabled(true);
        }
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('admin_userSuccess', array('user_id' => $user->getId()));
    }

    public function groupAction(){
        return $this->render('RASPRaspBundle:User/Gestion:group.html.twig');
    }

}