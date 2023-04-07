<?php

namespace App\Controller;

use DateTime;
use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductController extends AbstractController
{
    #[Route('/admin/ajouter-un-produit', name: 'create_product', methods: ['GET', 'POST'])]
    public function createProduct(Request $request, ProductRepository $repository, SluggerInterface $slugger): Response
    {
        $product = new Product();

        $form = $this->createForm(ProductFormType::class, $product)
            ->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {

            $product->setCreatedAt(new DateTime());
            $product->setUpdatedAt(new DateTime());

            # On variabilise le fichier de la photo en récupérant les données du formulaire (input photo)
            # On obtient un objet de type UploadedFile()
            /** @var UploadedFile $photo */ // - pour activer les actions get....
            $photo = $form->get('photo')->getData();

            if($photo) {
                $this->handleFile($product, $photo, $slugger);
            } // end if($photo)
            
            $repository->save($product, true);

            $this->addFlash('success', "Le produit a été ajouté !");
            return $this->redirectToRoute('dashboard'); // ¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤ A CHANGER
        } // end if($form)

        return $this->render('admin/product/form.html.twig', [ // ¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤ A CHANGER
            'form' => $form->createView()
        ]);
    } // end createProduct()

    #[Route('/modifier-un-produit/{id}', name: 'update_product', methods: ['GET', 'POST'])]
    public function updateProduct(Product $product, Request $request, ProductRepository $repository, SluggerInterface $slugger): Response
    {
        # Récupération de la photo non update
        $currentPhoto = $product->getPhoto();

        $form = $this->createForm(ProductFormType::class, $product, [
            'photo' => $currentPhoto,
            'constraints' => [
                new NotBlank([
                    'allowNull' => true,
                    'message' => 'La photo doit être une chaîne de caractères.',
                ]),
            ],
        ])->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $product->setUpdatedAt(new DateTime());

            $newPhoto = $form->get('photo')->getData();

            if($newPhoto) {
                $this->handleFile($product, $newPhoto, $slugger);
            } else {
                # Si pas de nouvelle photo, alors on re-set la photo déjà dans la BDD
                $product->setPhoto($currentPhoto);
            } // end if($newPhoto)

            $repository->save($product, true);

            $this->addFlash('success',"La modification a bien été enregistrée.");
            return $this->redirectToRoute('show_dashboard'); // ¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤ A CHANGER

        } // end if($form)
        
        return $this->render('admin/product/form.html.twig', [ // ¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤¤ A CHANGER
            'form' => $form->createView(),
            'product' => $product
        ]);
    } // end updateProduct()
    #[Route('/archiver-un-produit/{id}', name: 'soft_delete_product', methods: ['GET'])]
public function SoftDeleteProduct(Product $product, ProductRepository $repository): Response
{
    $product->setDeletedAt(new DateTime());

    $repository->save($product, true);

    $this->addFlash('success', "Le produit a bien été archivé");
    return $this->redirectToRoute('show_dashboard');

}

    # -------------------------------------- PRIVATE FUNCTIONS ----------------------------------------------
    private function handleFile(Product $product, UploadedFile $photo, SluggerInterface $slugger)
    {
        # 1 - Déconstruire le nom du fichier
        # a : Variabiliser l'extension du fichier
        $extension = '.' . $photo->guessExtension() ;

        # 2 - Assainir le nom du fichier (càd, retirer les accents et les espaces blancs)
        $safeFilename = $slugger->slug(pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME));

        # 3 - Rendre le nom du fichier unique
        # a : Reconstruire le nom du fichier
        $newFilename = $safeFilename . '_' . uniqid("", true) . $extension;

        # 4 - Déplacer le fichier (upload dans notre application Symfony)
        # On utilise le try/catch lorsqu'une méthode lance (throw) une Exception (erreur)
        try {
            # On a défini un paramètre dans config/service.yaml qui est le chemin (absolu) du dossier 'uploads'
            # On récupère la valeur (le paramètre) avec getParameter() et le nom du param défini dans le fichier service.yaml.
            $photo->move($this->getParameter('uploads_dir'), $newFilename);
            # Si tout s'est bien passé (aucune Exception lancée) alors on doit set le nom de la photo en BDD
            $product->setPhoto($newFilename);
        }
        catch(FileException $exception) {
            $this->addFlash('warning', "Le fichier ne s'est pas importé correctement. Veuillez réessayer." . $exception->getMessage());
        } // end catch()
    } // end handleFile()
    # -------------------------------------- PRIVATE FUNCTIONS FIN -------------------------------------------

} // end class