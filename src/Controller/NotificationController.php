<?php

namespace App\Controller;

use App\Entity\Notifications;
use App\Repository\NotificationsRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/notification")
 */
class NotificationController extends Controller
{
    /**
     * @var NotificationsRepository
     */
    public $notificationsRepository;

    public function __construct (NotificationsRepository $notificationsRepository)
    {
        $this->notificationsRepository = $notificationsRepository;
    }

    /**
     * @Route("/all",name="all_notifications")
     */
    public function index ()
    {

        return $this->render("notification/notifications.html.twig",[
            "notifications" => $this->notificationsRepository->findBy([
                "user"  => $this->getUser(),
                "seen"  => false,
            ])
        ]);

    }

    /**
     * @Route("/unread-count",name="notification_unread")
     */
    public function unreadCount ()
    {
    
        return new JsonResponse([
           "count"  => $this->notificationsRepository->findUnseenByUser($this->getUser())
        ]);
    
    }

    /**
     * @Route("/acknowledge/{id}",name="notification_acknowledge")
     */
    public function acknowledge (Notifications $notifications)
    {
    
        $notifications->setSeen(True);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute("all_notifications");
    
    }

}
