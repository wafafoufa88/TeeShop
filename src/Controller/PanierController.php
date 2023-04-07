<?php

namespace App\Controller;

use DateTime;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/panier')]
class PanierController extends AbstractController
{
    #[Route('/voir-mon-panier', name: 'show_panier',methods:['GET'])]
    public function showPanier(SessionInterface $session): Response
    {
        # cette istruction permet de récuperer le panier en session, et à défaut crée le panier s'il n'existe pas 
        $panier = $session->get('panier',[]);

        $total = 0;
        # EXERCICE : Faire le code qui permet de calculer le total par produit ET le total du panier
        foreach($panier as $item){
            
            $totalItem = $item['product']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }
        return $this->render('panier/show_panier.html.twig',[
            'total'=> $total
        ]);
        return $this->render('panier/show_panier.html.twig',);
        
    
    }//end fuction showPanier
    #[Route('/ajouter-un-produit/{id}', name: 'add_item', methods: ['GET'])]
    public function addItem(Product $product, SessionInterface $session)
    {
        #si dans la session le panier n'existe pas ,la méthode get() retournera le second paramétre : un array vide
        $panier = $session->get('panier', []);

        if(empty($panier[$product->getId()])) {
            
            $panier[$product->getId()]['quantity'] = 1;
            $panier[$product->getId()]['product'] = $product;

        } else {
            // post-incrementation:quantity+1
            //pre-incrementation:1+quantity
            ++$panier[$product->getId()]['quantity'];
            
        }
        //ici,nous devons set() le panier en session,en lui passant $panier[]
        $session->set('panier', $panier);
        $this->addFlash('success',"le produit a bien été ajouté à votre panier !");
        return $this->redirectToRoute('Show_home');
    
    }// end function addItem
    #[Route('/vider-le-panier', name:'delete_panier', methods:['Get'])]
    public function deletePanier(SessionInterface $session): Response
    {
            $session->remove('panier');

            $this->addFlash('success',"Votre panier est vide.");
            
            return $this->redirectToRoute('Show_home');
        }
        #[Route('/retirer-du-panier/{id}',name:'delete_item',methods:['GET'])]
        public function deleteItem(int $id, SessionInterface $session): Response
        {
            $panier = $session->get('panier');
            //array_key_exists()permet de v"rifier si une key existe dans un array.
            if(array_key_exists($id,$panier)) {
                unset($panier[$id]);// unset() permet de supprimer une variable (ou une clé dans array)

            }
            $session->set('panier',$panier);
            return $this->redirectToRoute('show_panier');
        }// end function valid
        #[Route('/valider-mon-panier', name: 'validate_commande', methods: ['GET'])]
        public function validateCommande(SessionInterface $session, EntityManagerInterface $entityManager): Response
        {
            $panier = $session->get('panier', []);
    
            if(empty($panier)) {
                $this->addFlash('warning', 'Votre panier est vide.');
                return $this->redirectToRoute('show_panier');
            }
    
            $commande = new Command();
    
            $commande->setCreatedAt(new DateTime());
            $commande->setUpdatedAt(new DateTime());
    
            $total = 0;
    
    
            foreach($panier as $item) {
                $totalItem = $item['product']->getPrice() * $item['quantity'];
                $total += $totalItem;
    
                $commande->setQuantity($item['quantity']);
    //            $product = $item['produit'];
            }
    
    //        $commande->setProduct($product);
    
    
            $commande->setStatus('en préparation');
            $commande->setUser($this->getUser());
            $commande->setTotal($total);
    
            $entityManager->persist($commande);
            $entityManager->flush();
    
            $session->remove('panier');
    
            $this->addFlash('success', "Bravo, votre commande est prise en compte et en préparation. Vous pouvez la retrouver dans Mes Commandes.");
            return $this->redirectToRoute('show_home');
    
        }// end function validate()
}//end class
