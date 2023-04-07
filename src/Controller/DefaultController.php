<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'Show_home', methods:['GET'])]
    public function ShowHome(ProductRepository $repository): Response
    {
       $products = $repository->findBy(['deletedAt'=> null]);
       
        return $this->render('default/Show_home.html.twig', [
            'products' => $products
        ]);
    }
}
