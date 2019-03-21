<?php
 
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Gestionnaire;
use App\Entity\Logement;
use App\Entity\Occupant;
use App\Entity\Location;
use App\Repository\GestionnaireRepository;
use App\Repository\LogementRepository;
use App\Form\GestionnaireType;
use App\Form\LogementType;
use App\Form\LocationType;
use App\Repository\LocationRepository;
use App\Repository\DispositifRepository;


class AdminController extends AbstractController
{
    /**
     * @Route("admin", name="admin_gestionnaires_show")
     */
    public function adminGestionnairesShow(GestionnaireRepository $gestionnaire_repo)
    {
        $gestionnaires = $gestionnaire_repo->findAll();
        return $this->render('admin/admin_gestionnaires_show.html.twig', [
            'gestionnaires' => $gestionnaires
        ]);
    }

    /**
     * @Route("admin/dispositifs", name="admin_dispositifs_show")
     */
    public function adminDispositifsShow(DispositifRepository $dispositif_repo)
    {
        $dispositifs = $dispositif_repo->findAll();
        return $this->render('admin/admin_dispositifs_show.html.twig', [
            'dispositifs' => $dispositifs
        ]);
    }

    /**
     * @Route("admin/gestionnaire/{id}", name="admin_gestionnaire_show")
     */
    public function adminGestionnaireShow(Gestionnaire $gestionnaire, Request $request, ObjectManager $manager)
    {
        $gestionnaireForm = $this->createForm(GestionnaireType::class, $gestionnaire);
        $gestionnaireForm->handlerequest($request);

        if ($gestionnaireForm->isSubmitted() && $gestionnaireForm->isValid()) {
            $manager->persist($gestionnaire);
            $manager->flush();
            $this->addFlash('success', 'Gestionnaire édité');
            return $this->redirectToRoute('admin_gestionnaire_show', ['id' => $gestionnaire->getId()]);
        }

        return $this->render('admin/admin_gestionnaire_show.html.twig', [
            'gestionnaire' => $gestionnaire,
            'gestionnaireForm' => $gestionnaireForm-> createView()
        ]);
    }

    /**
     * @Route("admin/logement/{id}", name="admin_logement_show")
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

        return $this->render('admin/admin_logement_show.html.twig', [
            'logement' => $logement,
            'locations' => $locations,
            'logementForm' => $logementForm->createView(),
            'locationForm' => $locationForm->createView(),
        ]);
    }
}