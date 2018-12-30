<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 9/5/2018
 * Time: 4:31 PM
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends Controller
{

    /**
     * @Route("/register",name="user_register")
     */
    public function register (Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        // Create user instance
        $user = new User();

        // Create Form
        $form = $this->createForm(UserType::class,$user);

        // Handle Request
        $form->handleRequest($request);

        // Check form is submitted and form is valid or not
        if ($form->isSubmitted() && $form->isValid()){

            //First we take plain password when form is submitted
            //Then Encode Plain Password with Interface
            //And Set User Object @var $password with encoded password
            $password = $passwordEncoder->encodePassword($user,$user->getPlainPassword());
            //Setting encoded password to User object password property
            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->redirect('micro_post_index');
        }
        // Return a response and Render register page and form
        return $this->render('register/register.html.twig',[
            'form'  => $form->createView()
        ]);

    }

}