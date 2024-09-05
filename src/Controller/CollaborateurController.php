<?php

namespace App\Controller;

use App\Entity\Collaborateur;
use App\Form\CollaborateurType;
use App\Repository\CollaborateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/collaborateur')]
class CollaborateurController extends AbstractController
{
    #[Route('/', name: 'app_collaborateur_index', methods: ['GET'])]
    public function index(CollaborateurRepository $collaborateurRepository, Request $request): Response
    {
        // demande l'affichage des collaborateur plus un formulaire de recherche
        // on récupère les paralmetre de recherche et bouton non affecte
        $noAffect = $request->query->get('noAffect');
        $search = $request->query->get('search');
        // si le bouton non affecte est cliqué
        if($noAffect == true) $collaborateurs = $collaborateurRepository->searchNoAffect();
        // sinon, si search non null  on recupere la liste des collaborateur correspondant
        else if ($search) $collaborateurs = $collaborateurRepository->search($search);
        // sinon on récupère tout les collaborateurs
        else $collaborateurs = $collaborateurRepository->findAll();

        return $this->render('collaborateur/index.html.twig', [
            'collaborateurs' => $collaborateurs,
        ]);
    }


    #[Route('/new', name: 'app_collaborateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $collaborateur = new Collaborateur();
        $form = $this->createForm(CollaborateurType::class, $collaborateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on hash le mot de passe
            $hash = password_hash($collaborateur->getPassword(), PASSWORD_DEFAULT);
            $collaborateur->setPassword($hash);
            // si le collaborateur enregistré est administrateur, on lui ajoute le role d'administrateur
            if($collaborateur->isAdministrateur()) $collaborateur->setRoles(["ROLE_ADMIN"]);
            $entityManager->persist($collaborateur);
            $entityManager->flush();
            return $this->redirectToRoute('app_collaborateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('collaborateur/new.html.twig', [
            'collaborateur' => $collaborateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_collaborateur_show', methods: ['GET'])]
    public function show(Collaborateur $collaborateur): Response
    {
        // on récupère les affectations du collaborateur
        $affectations = $collaborateur->getAffectations();
        // on initialise les 2 tableaux d'affectations
        $affectationsActuelle = [];
        $affectationsPassees = [];
        // on parcours les affectations
        foreach ($affectations as $affectation) {
            // si la date de fin est null, on stock dans afectations actuelle,
            if ($affectation->getDateFin() === null) $affectationsActuelle[] = $affectation;
            // sinon on stock dans affectations passée
            else $affectationsPassees[] = $affectation;
        }


        return $this->render('collaborateur/show.html.twig', [
            'collaborateur' => $collaborateur,
            'affectationsActuelle' => $affectationsActuelle,
            'affectationsPassees' => $affectationsPassees,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_collaborateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Collaborateur $collaborateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CollaborateurType::class, $collaborateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // si le mdp n'est aps null, on le modifie en base
            if(!is_null($collaborateur->getPassword())) {
                $hash = password_hash($collaborateur->getPassword(), PASSWORD_DEFAULT);
                $collaborateur->setPassword($hash);
            };
            $entityManager->flush();

            return $this->redirectToRoute('app_collaborateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('collaborateur/edit.html.twig', [
            'collaborateur' => $collaborateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_collaborateur_delete', methods: ['POST'])]
    public function delete(Request $request, Collaborateur $collaborateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$collaborateur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($collaborateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_collaborateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
