<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 11/7/2018
 * Time: 8:57 PM
 */

namespace App\EventListener;

use App\Entity\FollowNotification;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;


class FollowNotificationSubscriber implements EventSubscriber
{

    public function getSubscribedEvents()
    {

        return [
            Events::onFlush
        ];

    }

    public function onFlush (OnFlushEventArgs $args)
    {

        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        /**
         * @var PersistentCollection $collectionUpdate
         */

        foreach ($uow->getScheduledCollectionUpdates() as $collectionUpdate) {


            if (!$collectionUpdate->getOwner() instanceof User &&
                $collectionUpdate->getMapping() != "following"
            ){
                continue;
            }

            $insertDiff = $collectionUpdate->getInsertDiff();

            if (!count($insertDiff)){
                return;
            }

            /** User $user */
            $user = $collectionUpdate->getOwner();

            $notification = new FollowNotification();
            $notification->setUser(reset($insertDiff));
            $notification->setFollowedBy($user);

            $em->persist($notification);

            $uow->computeChangeSet(
                $em->getClassMetadata(FollowNotification::class),
                $notification
            );

        }

    }
}