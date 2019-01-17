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


class AdminController extends AbstractController
{
    /**
     * @Route("admin/logements", name="admin_logements_show")
     */
    public function adminLogementsShow(LogementRepository $logement_repo)
    {
        $logements = $logement_repo->findAll();
        return $this->render('hebergement/admin_logements_show.html.twig', [
            'logements' => $logements
        ]);
    }

    /**
     * @Route("admin/gestionnaires", name="admin_gestionnaires_show")
     */
    public function adminGestionnairesShow(GestionnaireRepository $gestionnaire_repo)
    {
        $gestionnaires = $gestionnaire_repo->findAll();
        return $this->render('hebergement/admin_gestionnaires_show.html.twig', [
            'gestionnaires' => $gestionnaires
        ]);
    }
}