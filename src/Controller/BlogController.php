<?php
/**
 * Created by PhpStorm.
 * User: Hp
 * Date: 8/6/2018
 * Time: 5:19 PM
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{

    /**
     * @Route("/blog",name="blog_index")
     */

    public function index ()
    {

        return $this->render("base.html.twig");

    }

}