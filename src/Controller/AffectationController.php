<?php

namespace App\Controller;

use App\Entity\Affectation;
use App\Form\AffectationType;
use App\Form\SearchAffectationType;
use App\Repository\AffectationRepository;
use App\Repository\CollaborateurRepository;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/affectation')]
class AffectationController extends AbstractController
{
    #[Route('/', name: 'app_affectation_index', methods: ['GET','POST'])]
    public function index(Request $request, AffectationRepository $affectationRepository): Response
    {
        // on crée le formulaire
        $form = $this->createForm(SearchAffectationType::class, null, [
            'method' => 'GET',
        ]);
        // on récupère la requète
        $form->handleRequest($request);
        // si le formulaire est soumis et valide, on construit la liste des affectations en fonction
        if ($form->isSubmitted() && $form->isValid()) $affectations = $affectationRepository->searchFiltres($form);
        // sinon on récupère toute les affectations
        else $affectations = $affectationRepository->findAll();
        
        // on retourne le template et fournit la liste des affectations et le formulaire
        return $this->render('affectation/index.html.twig', [
            'affectations' => $affectations,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_affectation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CollaborateurRepository $collaborateurRepository, RestaurantRepository $restaurantRepository): Response
    {
        $affectation = new Affectation();

        // récupération du collaborateur et du restaurant si on les a en aprametre et on le charge dans le champ collaborateur ou restaurant de l'affectationchargement, idem avec le restaurant
        if(!is_null($request->query->get("collaborateurId"))){
            $collaborateur = $collaborateurRepository->find($request->query->get("collaborateurId"));
            if ($collaborateur) $affectation->setCollaborateur($collaborateur);
        }
        if(!is_null($request->query->get("restaurantId"))){
            $restaurant = $restaurantRepository->find($request->query->get("restaurantId"));
            if ($restaurant) $affectation->setRestaurant($restaurant);
        }       

        // on crée le formulaire
        $form = $this->createForm(AffectationType::class, $affectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($affectation);
            $entityManager->flush();

            return $this->redirectToRoute('app_affectation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('affectation/new.html.twig', [
            'affectation' => $affectation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_affectation_show', methods: ['GET'])]
    public function show(Affectation $affectation): Response
    {
        return $this->render('affectation/show.html.twig', [
            'affectation' => $affectation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_affectation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Affectation $affectation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AffectationType::class, $affectation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_affectation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('affectation/edit.html.twig', [
            'affectation' => $affectation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_affectation_delete', methods: ['POST'])]
    public function delete(Request $request, Affectation $affectation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$affectation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($affectation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_affectation_index', [], Response::HTTP_SEE_OTHER);
    }
}
