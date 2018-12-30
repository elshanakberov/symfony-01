<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FollowNotificationRepository")
 */
class FollowNotification extends Notifications
{

    /**
     * @ORM\ManyToOne("App\Entity\User")
     */
    private $followed_by;

    /**
     * @return mixed
     */
    public function getFollowedBy()
    {
        return $this->followed_by;
    }

    /**
     * @param mixed $followed_by
     */
    public function setFollowedBy($followed_by): void
    {
        $this->followed_by = $followed_by;
    }



}
