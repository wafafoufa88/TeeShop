<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'Show_home', methods:['GET'])]
    public function ShowHome(): Response
    {
        return $this->render('default/Show_home.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }
}
