<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 10/19/2018
 * Time: 6:40 PM
 */

namespace App\EventListener;


use App\Entity\LikeNotification;
use App\Entity\MicroPost;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\PersistentCollection;


class LikeNotificationSubscriber implements EventSubscriber
{

    // Returning methods which our Subscriber is subscribe to
    public function getSubscribedEvents()
    {
         return [
           Events::onFlush
         ];
    }

    // Method name has to match event name.
    // You cannot call it differently
    public function onFlush (OnFlushEventArgs $args)
    {
        //Param $args is return useful info when new data persisted. in our case Liked

        $em = $args->getEntityManager();
        // Unit of work keeps track of all the changes to the all the entities.
        // That is why we use it to know whether changes made entities or not
        $uow = $em->getUnitOfWork();
        /**
         * @var PersistentCollection $collectionUpdate
         * Lists all the Objects which uses Doctrine Collection Interface
         */
        foreach ($uow->getScheduledCollectionUpdates() as $collectionUpdate){

            // getOwner() method returns owner of the collection
            if(!$collectionUpdate->getOwner() instanceof MicroPost){
                continue;
            }

           if("likedBy" != $collectionUpdate->getMapping()["fieldName"]){
                continue;
            }

            // It will be an Array. Array of elements that added to the collection
            $insertDiff = $collectionUpdate->getInsertDiff();

            if (!count($insertDiff)){
                return;
            }
            /** @var MicroPost $microPost */
            $microPost = $collectionUpdate->getOwner();

            $notification  = new LikeNotification();
            $notification->setUser($microPost->getUser());
            $notification->setMicroPost($microPost);
            $notification->setLikedBy(reset($insertDiff));

            $em->persist($notification);

            $uow->computeChangeSet(
                $em->getClassMetadata(LikeNotification::class),
                $notification
            );

        }

    }
    
}