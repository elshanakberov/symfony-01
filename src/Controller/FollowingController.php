<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 10/5/2018
 * Time: 6:47 PM
 */

namespace App\Controller;


use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/following")
 */
class FollowingController extends Controller
{

    /**
     * @Route("/follow/{id}",name="following_follow")
     */
    public function follow (User $userToFollow)
    {
        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if ($currentUser->getId() !== $userToFollow->getId()
          && $currentUser->getFollowing()->contains($userToFollow) == false
        ) {

            $currentuser->getfollowing()->add($usertofollow);

            $this->getdoctrine()->getmanager()->flush();

        }

        return $this->redirectToRoute(
            "micro_post_by_user",
            ["username" => $userToFollow->getUsername()]
        );

    }

    /**
     * @Route("/unfollow/{id}",name="following_unfollow")
     */
    public function unfollow (User $userToUnfollow)
    {
        /**
         * @var User $currentUser
         */
        $currentUser = $this->getUser();

        if ($userToUnfollow !== $currentUser->getId()) {

            $currentUser->getFollowing()->removeElement($userToUnfollow);

            $this->getDoctrine()->getManager()->flush();

        }

        return $this->redirectToRoute(
            "micro_post_by_user",
            ["username" => $userToUnfollow->getUsername()]
        );

    }

}