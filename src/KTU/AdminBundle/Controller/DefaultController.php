<?php

namespace KTU\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('KTUAdminBundle:Default:index.html.twig', array());
    }
}
