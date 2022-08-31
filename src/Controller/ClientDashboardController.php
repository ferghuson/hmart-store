<?php

namespace App\Controller;

use App\Form\NewPasswordType;
use App\Form\UserProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ClientDashboardController extends AbstractController
{
    private $entityManager, $alert = null, $alertMessage = null;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/client/dashboard', name: 'app_client_dashboard')]
    public function index(): Response
    {
        if (isset($_GET['registration'])){
            $this->alert = 'success';
            $this->alertMessage = 'Félicitation... votre inscription est maintenant complète.';
        }

        return $this->render('client_dashboard/index.html.twig', [
            'alert' => $this->alert,
            'alert_message' => $this->alertMessage
        ]);
    }

    #[Route('/client/profile', name: 'app_client_profile')]
    public function editProfile(Request $request): Response
    {
        $alert = null; $alertMessage = null;
        $user = $this->getUser();
        $form = $this->createForm(UserProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->alert = 'success';
            $this->alertMessage = 'Votre profil a été mis à jour avec succès.';
        }

        return $this->render('client_dashboard/profile.html.twig', [
            'profile_form' => $form->createView(),
            'alert' => $this->alert,
            'alert_message' => $this->alertMessage
        ]);
    }

    #[Route('/client/new-password', name: 'app_client_password')]
    public function newPassword(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $alert = null; $alertMessage = null;
        $user = $this->getUser();
        $form = $this->createForm(NewPasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $old_pwd = $form->get('old_password')->getData();

            if($hasher->isPasswordValid($user, $old_pwd)){

                $new_pwd = $form->get('new_password')->getData();

                $user->setPassword($hasher->hashPassword($user, $new_pwd));

                $this->entityManager->flush();

                $this->alert = 'success';
                $this->alertMessage = 'Votre mot de passe a bien été mis à jour.';
            }else{
                $this->alert = 'danger';
                $this->alertMessage = "Votre mot de actuel est incorrect. Veuillez réessayer.";
            }

        }

        return $this->render('client_dashboard/password.html.twig', [
            'pwd_form' => $form->createView(),
            'alert' => $this->alert,
            'alert_message' => $this->alertMessage
        ]);
    }
}
