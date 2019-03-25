<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Gestionnaire;
use App\Entity\Logement;
use App\Form\LogementType;
use App\Entity\Occupant;
use App\Form\OccupantType;
use App\Form\LocationType;
use App\Entity\Location;
use App\Repository\GestionnaireRepository;
use App\Repository\LocationRepository;


class HebergementController extends AbstractController
{
    /**
     * @Route("/", name="logements_show")
     */
    public function logementsShow(Logement $logement = null, GestionnaireRepository $gestionnaire_repo, Request $request, ObjectManager $manager)
    {
        $gestionnaire = $gestionnaire_repo->findOneById($this->getUser()->getId());
        $logements = $gestionnaire->getLogements();

        $logement = new Logement();
        $logementForm = $this->createForm(LogementType::class, $logement);
        $logementForm->handlerequest($request);

        if ($logementForm->isSubmitted() && $logementForm->isValid()) {
            $logement->setGestionnaire($gestionnaire);
            $manager->persist($logement);
            $manager->flush();
            $this->addFlash('success', 'Logement ajouté');
            return $this->redirectToRoute('logement_show', ['id' => $logement->getId()]);
        }

        return $this->render('logements_show.html.twig', [
            'logementForm' => $logementForm->createView(),
            'logements' => $logements,
        ]);
    }

    /**
     * @Route("logement/{id}", name="logement_show")
     */
    public function logementShow(Logement $logement, Request $request, LocationRepository $location_repo, ObjectManager $manager)
    {
        
        // On empêche l'utilisateur d'accéder à d'autres logements que les siens
        if($logement->getGestionnaire()->getId() != $this->getUser()->getId() ) {
            return $this->redirectToRoute('logements_show');
        }
        if (!$logement) {
            throw $this->createNotFoundException('Unable to find Link entity with id: ');
        }
        
        $logementForm = $this->createForm(LogementType::class, $logement);
        $logementForm->handlerequest($request);

        if ($logementForm->isSubmitted() && $logementForm->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'Logement édité');
            return $this->redirectToRoute('logement_show', ['id' => $logement->getId()]);
        }

        $locations = $location_repo->findByLogement($logement->getId());
        $location = new Location();
        $locationForm = $this->createForm(LocationType::class, $location);
        $locationForm->handleRequest($request);

        if ($locationForm->isSubmitted() && $locationForm->isValid()) {
            $location->setLogement($logement);
            $location->setCreatedAt(new \DateTime('now'));
            $manager->persist($location);
            $manager->flush();
            $this->addFlash('success', 'Occupant ajouté');
            return $this->redirectToRoute('logement_show', ['id' => $logement->getId()]);
        }

        return $this->render('logement_show.html.twig', [
            'logement' => $logement,
            'locations' => $locations,
            'logementForm' => $logementForm->createView(),
            'locationForm' => $locationForm->createView(),
        ]);
    }

    /**
     * @Route("logement/{id}/delete", name="logement_delete", methods="DELETE")
     */
    public function logementDelete(Logement $logement, Request $request, ObjectManager $manager)
    {
        if ($this->isCsrfTokenValid('delete-' . $logement->getId(), $request->get('_token'))) {
            $manager->remove($logement);
            $manager->flush();
            $this->addFlash('success', "Logement supprimé");
        } else {
            $this->addFlash('danger', 'URL Invalide');
            return $this->redirectToRoute('logements_show');
        }
        return $this->redirectToRoute('logements_show');
    }

    /**
     * @Route("occupant/{id}/delete", name="occupant_delete", methods="DELETE")
     */
    public function occupantDelete(Occupant $occupant, Request $request, ObjectManager $manager)
    {
        $logement = $request->get('logementId');
        if ($this->isCsrfTokenValid('delete-' . $occupant->getId(), $request->get('_token'))) {
            $manager->remove($occupant);
            $manager->flush();
            $this->addFlash('success', 'Occupant supprimé');
        } else {
            $this->addFlash('danger', 'URL Invalide');
            return $this->redirectToRoute('logement_show', ['id' => $logement]);
        }
        return $this->redirectToRoute('logement_show', ['id' => $logement]);
    }

    /**
     * @Route("occupant/{id}/edit", name="occupant_edit")
     */
    public function occupantEdit(Location $location, Request $request, ObjectManager $manager)
    {
        $logement = $location->getLogement();
        $occupantForm = $this->createForm(LocationType::class, $location);
        $occupantForm->handlerequest($request);

        if ($occupantForm->isSubmitted() && $occupantForm->isValid()) {
            $manager->persist($location);
            $manager->flush();
            $this->addFlash('success', 'Occupant édité');
            return $this->redirectToRoute('logement_show', ['id' => $logement->getId()]);
        }

        return $this->render('location_show.html.twig', [
            'occupantForm' => $occupantForm->createView()
        ]);

    }
}