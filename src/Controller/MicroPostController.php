<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 8/11/2018
 * Time: 6:27 PM
 */

namespace App\Controller;

use App\Entity\MicroPost;
use App\Entity\User;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\RouterInterface;

/**
 * @Route("/micro-post")
 */

class MicroPostController extends AbstractController
{

    /**
     * @var MicroPostRepository
     */
    private $microPostRepository;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct (MicroPostRepository $microPostRepository,FormFactoryInterface $formFactory,
    EntityManagerInterface $entityManager,RouterInterface $router)
    {
        $this->microPostRepository = $microPostRepository;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * @Route("/",name="micro_post_index")
     */
    public function index ()
    {
        $currentUser = $this->getUser();

        if($currentUser instanceof User){

           $posts = $this->microPostRepository->findAllByUsers(
               $currentUser->getFollowing()
           );


        }else{

            $posts = $this->microPostRepository->findBy(
                [],
                ['time'=>'DESC']
            );

        }

       return $this->render("/micro-post/index.html.twig",[
            "posts" => $posts
        ]);

    }

    /**
     * @Route("/add",name="micro_post_add")
     * @Security("is_granted('ROLE_USER')")
     */
    public function add(Request $request)
    {
        $user = $this->getUser();
        $microPost = new MicroPost();
        $microPost->setUser($user);

        $form = $this->formFactory->create(MicroPostType::class,$microPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($microPost);
            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('micro_post_index'));

        }

        return $this->render("micro-post/add.html.twig",[
             "form" => $form->createView()
        ]);

    }

    /**
     * @Route("/edit/{id}",name="micro_post_edit")
     */
    public function edit (MicroPost $microPost,Request $request)
    {

        $form = $this->formFactory->create(MicroPostType::class,$microPost);
        $form->handleRequest($request);
        $this->denyAccessUnlessGranted('edit',$microPost);
        if ($form->isSubmitted() && $form->isValid()){

            $this->entityManager->flush();

            return new RedirectResponse($this->router->generate('micro_post_index'));

        }

        return $this->render("micro-post/add.html.twig",[
            "form" => $form->createView()
        ]);

    }

    /**
     * @Route("/{id}",name="micro_post_post")
     */
    public function post (MicroPost $post)
    {
            return $this->render("micro-post/post.html.twig",[
                'post'  => $post
            ]);
    }

    /**
     * @Route("/user/{username}",name="micro_post_by_user")
     */
    public function userPosts (User $user)
    {
        return $this->render("micro-post/user-post.html.twig",[
            'posts' => $this->microPostRepository->findBy(
                ["user" => $user],
                ["time" => "DESC"]
            ),
           'user'  => $user
           // "posts"  => $user->getPosts()
        ]);
    }

}