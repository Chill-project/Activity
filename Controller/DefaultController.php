<?php

namespace Chill\ActivityBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('ChillActivityBundle:Default:index.html.twig', array('name' => $name));
    }
}
