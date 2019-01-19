<?php
 
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Gestionnaire;
use App\Entity\Logement;
use App\Entity\Occupant;
use App\Repository\GestionnaireRepository;
use App\Repository\LogementRepository;
use App\Form\GestionnaireType;


class AdminController extends AbstractController
{
    /**
     * @Route("admin/logements", name="admin_logements_show")
     */
    public function adminLogementsShow(LogementRepository $logement_repo)
    {
        $logements = $logement_repo->findAll();
        return $this->render('admin/admin_logements_show.html.twig', [
            'logements' => $logements
        ]);
    }

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
     * @Route("admin/gestionnaire/{id}", name="admin_gestionnaire_show")
     */
    public function adminGestionnaireShow(Gestionnaire $gestionnaire, Request $request, ObjectManager $manager)
    {
        $gestionnaireForm = $this->createForm(GestionnaireType::class, $gestionnaire);
        $gestionnaireForm->handlerequest($request);

        if ($gestionnaireForm->isSubmitted() && $gestionnaireForm->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'Gestionnaire édité');
            return $this->redirectToRoute('admin_gestionnaire_show', ['id' => $gestionnaire->getId()]);
        }

        return $this->render('admin/admin_gestionnaire_show.html.twig', [
            'gestionnaire' => $gestionnaire,
            'gestionnaireForm' => $gestionnaireForm-> createView()
        ]);
    }
}