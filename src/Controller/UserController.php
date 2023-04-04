<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\RegisterFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/inscription', name: 'register', methods: ['GET','POST'])]
    public function register(Request $request, UserRepository $repository, UserPasswordHasherInterface $passewordHasher): Response
    {
        $user = new User();
        
        $form =$this ->createForm(RegisterFormType::class, $user)
            ->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
# set les propriétes qui ne sont pas dans le formulaire et obligatoire en bdd.
            $user->setCreatedAt(new DateTime());
            $user->setUpdatedAt(new DateTime());
            # set les roles du User.cette propriété est un array[].
            $user->setRoles(['ROLE_USER']);
            #on doit resseter manuellement la valeur dur password, car par défaut il n'est pas hashé.
            #pour cela ,nous devons attend 2 argument:$user, $plainPassord
            #   => cette méthode attend 2 arguments : $user, $plainPassword
            $user->setPassword(
                $passewordHasher->hashPassword($user, $user->getPassword())
            );

            $repository->save($user, true);

            $this->addFlash('succes', "votre inscription a été correctement enregistée !");

            return $this->redirectToRoute('Show_home');

        }
        
        return $this->render('user/register_form.html.twig', [
            'form' => $form->createView()
        ]);
    } // end register()
} //end class
