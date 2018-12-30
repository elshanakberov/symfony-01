<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 10/17/2018
 * Time: 10:36 PM
 */

namespace App\Controller;


use App\Entity\MicroPost;
use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/likes")
 */
class LikesController extends Controller
{
    /**
     * @Route("like/{id}",name="likes_like")
     */
    public function like (MicroPost $microPost)
    {
        //Get Current user. @returns Logged in user or anonymous user
        $currentUser = $this->getUser();
        //Check if currentUser is instance of User entity.Means if user logged in
        if (!$currentUser instanceof User){
            return new JsonResponse([],Response::HTTP_UNAUTHORIZED);
        }
        //Adds logged in user to likedBy Collection
        $microPost->like($currentUser);
        $this->getDoctrine()->getManager()->flush();
        // Returns JsonResponse with count message which taken from Collection
        return new JsonResponse([
            "count" => $microPost->getLikedBy()->count()
        ]);
    }

    /**
     * @Route("/unlike/{id}",name="likes_unlike")
     */
    public function unlike (MicroPost $microPost)
    {

        $currentUser = $this->getUser();

        if (!$currentUser instanceof User){
            return new JsonResponse([],Response::HTTP_UNAUTHORIZED);
        }

        $microPost->getLikedBy()->removeElement($currentUser);
        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse([
            "count" => $microPost->getLikedBy()->count()
        ]);

    }

}