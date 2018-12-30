<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 11/6/2018
 * Time: 11:48 PM
 */

namespace App\Twig;


use App\Entity\FollowNotification;
use App\Entity\LikeNotification;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension
{

    public function getTests ()
    {
        return [
            new \Twig_SimpleTest(
                "like",
                function ($obj){return $obj instanceof LikeNotification;}
                ),
            new \Twig_SimpleTest(
                "follow",
                function ($obj){return $obj instanceof FollowNotification;}
            )
        ];

    }


}