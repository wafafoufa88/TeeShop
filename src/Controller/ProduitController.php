<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    #[Route('/admin/ajouter-un-produit', name: 'create_produit', methods: ['GET', 'POST'])]
    public function creatProduct(Request $request,ProductRepository $repository): Response
    {
        $produit = new Product();

        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }
}
