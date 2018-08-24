<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ObjetsPerdusController extends Controller
{
    public function indexAction()
    {
        return $this->render('@App/objets_perdus/index.html.twig');
    }
}
