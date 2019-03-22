<?php
 
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Gestionnaire;
use App\Entity\Dispositif;
use App\Entity\Logement;
use App\Entity\Occupant;
use App\Entity\Location;
use App\Repository\GestionnaireRepository;
use App\Repository\DispositifRepository;
use App\Repository\LogementRepository;
use App\Repository\LocationRepository;
use App\Form\GestionnaireType;
use App\Form\DispositifType;
use App\Form\RegistrationType;
use App\Form\LogementType;
use App\Form\LocationType;


class AdminController extends AbstractController
{

    /**
    * @Route("admin/dispositifs", name="admin_dispositifs_show")
    */
    public function adminDispositifsShow(DispositifRepository $dispositif_repo, Request $request, ObjectManager $manager)
    {
        $dispositifs = $dispositif_repo->findAll();

        $dispositif = new Dispositif();
        $form = $this->createForm(DispositifType::class, $dispositif);
        $form->handlerequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($dispositif);
            $manager->flush();
            $this->addFlash('success', 'Le dispositif a bien été ajouté');
            return $this->redirectToRoute('admin_dispositifs_show');
        }

        return $this->render('admin/admin_dispositifs_show.html.twig', [
            'dispositifs' => $dispositifs,
            'dispositifForm' => $form->createView()
        ]);
    }

    /**
    * @Route("admin/dispositif/{id}", name="admin_dispositif_show")
    */
    public function adminDispositifShow(Dispositif $dispositif, Request $request, ObjectManager $manager)
    {
        $dispositifForm = $this->createForm(DispositifType::class, $dispositif);
        $dispositifForm->handlerequest($request);

        if ($dispositifForm->isSubmitted() && $dispositifForm->isValid()) {
            $manager->persist($dispositif);
            $manager->flush();
            $this->addFlash('success', 'Dispositif édité');
            return $this->redirectToRoute('admin_dispositif_show', ['id' => $dispositif->getId()]);
        }

        return $this->render('admin/admin_dispositif_show.html.twig', [
            'dispositif' => $dispositif,
            'dispositifForm' => $dispositifForm-> createView()
        ]);
    }

    /**
     * @Route("admin/gestionnaires", name="admin_gestionnaires_show")
     */
    public function adminGestionnairesShow(
        Request $request,
        ObjectManager $manager,
        UserPasswordEncoderInterface $encoder,
        GestionnaireRepository $gestionnaire_repo
        )
    {
        $gestionnaires = $gestionnaire_repo->findAll();

        $gestionnaire = new Gestionnaire();
        $form = $this->createForm(RegistrationType::class, $gestionnaire);
        $form->handlerequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($gestionnaire, $gestionnaire->getPassword());
            $gestionnaire->setPassword($hash);
            $gestionnaire->setRoles(array("ROLE_ADMIN"));
            $manager->persist($gestionnaire);
            $manager->flush();
            $this->addFlash('success', 'Le gestionnaire a bien été ajouté');
            return $this->redirectToRoute('admin_gestionnaires_show');
        }

        return $this->render('admin/admin_gestionnaires_show.html.twig', [
            'gestionnaires' => $gestionnaires,
            'form' => $form->createView()
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

    /**
     * @Route("admin/logement/{id}/delete", name="admin_logement_delete", methods="DELETE")
     */
    public function adminLogementDelete(Logement $logement, Request $request, ObjectManager $manager)
    {
        $gestionnaire = $logement->getGestionnaire();
        if ($this->isCsrfTokenValid('delete-' . $logement->getId(), $request->get('_token'))) {
            $manager->remove($logement);
            $manager->flush();
            $this->addFlash('success', "Logement supprimé");
        } else {
            $this->addFlash('danger', 'URL Invalide');
            return $this->redirectToRoute('admin_gestionnaire_show', ['id' => $gestionnaire->getId()]);
        }
        return $this->redirectToRoute('admin_gestionnaire_show', ['id' => $gestionnaire->getId()]);
    }

    /**
    * @Route("admin/occupant/{id}/edit", name="admin_occupant_edit")
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
            return $this->redirectToRoute('admin_logement_show', ['id' => $logement->getId()]);
        }

        return $this->render('location_show.html.twig', [
            'occupantForm' => $occupantForm->createView()
        ]);
    }

    /**
     * @Route("occupant/{id}/delete", name="admin_occupant_delete", methods="DELETE")
     */
    public function adminOccupantDelete(Occupant $occupant, Request $request, ObjectManager $manager)
    {
        $logement = $request->get('logementId');
        if ($this->isCsrfTokenValid('delete-' . $occupant->getId(), $request->get('_token'))) {
            $manager->remove($occupant);
            $manager->flush();
            $this->addFlash('success', 'Occupant supprimé');
        } else {
            $this->addFlash('danger', 'URL Invalide');
            return $this->redirectToRoute('admin_logement_show', ['id' => $logement]);
        }
        return $this->redirectToRoute('admin_logement_show', ['id' => $logement]);
    }

}