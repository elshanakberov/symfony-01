<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 9/20/2018
 * Time: 6:26 PM
 */

namespace App\Security;


use App\Entity\MicroPost;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MicroPostVoter extends Voter
{

    const EDIT = 'edit';
    /**
     * @var AccessDecisionManagerInterface
     */
    public $decisionManager;


    public function __construct (AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed $subject The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support,return 0
        if(!in_array($attribute,array(self::EDIT))){
            return false;
        }
        //only vote on MicroPost object inside this Voter
        if(!$subject instanceof MicroPost){
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     * It is safe to assume that $attribute and $subject already passed the "supports()" method check.
     *
     * @param string $attribute
     * @param mixed $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        if($this->decisionManager->decide($token, [USER::ROLE_ADMIN])){
            return true;
        }

        $user = $token->getUser();

        if (!$user instanceof User){
            //The user must be logged in,if not deny access
            return false;
        }
        //you know $subject is a MicroPost object, thanks to supports
        /** @var MicroPost $microPost*/
        $microPost = $subject;

        switch ($attribute){
            case self::EDIT:
                return $this->canEdit($microPost,$user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canEdit(MicroPost $microPost,User $user)
    {

        return $microPost->getUser()->getId() === $user->getId();

    }
}






