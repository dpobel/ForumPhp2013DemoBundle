<?php

namespace EzSystems\ForumPhp2013DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('EzSystemsForumPhp2013DemoBundle:Default:index.html.twig', array('name' => $name));
    }
}
