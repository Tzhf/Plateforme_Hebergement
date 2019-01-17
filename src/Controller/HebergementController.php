<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Gestionnaire;
use App\Entity\Logement;
use App\Entity\Occupant;
use App\Form\LogementType;
use App\Form\OccupantType;
use App\Repository\GestionnaireRepository;
use App\Repository\LocationRepository;
use App\Entity\Location;


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
        $logementForm = $this->createForm(LogementType::class, $logement);
        $logementForm->handlerequest($request);

        if ($logementForm->isSubmitted() && $logementForm->isValid()) {
            $manager->flush();

            $this->addFlash('success', 'Logement édité');
            return $this->redirectToRoute('logement_show', ['id' => $logement->getId()]);
        }

        $locations = $location_repo->findAllByLogementAndActive($logement->getId());

        $occupant = new Occupant();
        $occupantForm = $this->createForm(OccupantType::class, $occupant);
        $occupantForm->handleRequest($request);

        if ($occupantForm->isSubmitted() && $occupantForm->isValid()) {
            $manager->persist($occupant);

            $location = new Location();
            $location->setLogement($logement);
            $location->setOccupant($occupant);
            $location->setIsActive(1);
            $manager->persist($location);
            
            $manager->flush();

            $this->addFlash('success', 'Occupant ajouté');
            return $this->redirectToRoute('logement_show', ['id' => $logement->getId()]);
        }

        return $this->render('logement_show.html.twig', [
            'logement' => $logement,
            'locations' => $locations,
            'logementForm' => $logementForm->createView(),
            'occupantForm' => $occupantForm->createView()
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
     * @Route("occupant/{id}/edit", name="edit_occupant")
     */
    public function occupantEdit(Occupant $occupant = null, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(OccupantType::class, $occupant);
        $form->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($occupant);
            $manager->flush();
            $this->addFlash('success', 'Occupant édité');
        }

        return $this->render('saisie_occupant.html.twig', [
            'formoccupant' => $form->createView()
        ]);

    }
}