<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Entity\Gestionnaire;
use App\Form\RegistrationType;

class SecurityController extends AbstractController
{
    /**
     * @Route("inscription", name="inscription")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $gestionnaire = new Gestionnaire();
        $form = $this->createForm(RegistrationType::class, $gestionnaire);

        $form->handlerequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($gestionnaire, $gestionnaire->getPassword());

            $gestionnaire->setPassword($hash);
            $gestionnaire->setRoles(array("ROLE_ADMIN"));
            $manager->persist($gestionnaire);
            $manager->flush();
            $this->addFlash('success', 'Votre inscription a été prise en compte, veuillez vous connecter.');
            return $this->redirectToRoute('connexion');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("options/{id}", name="options")
     */
    public function account(Gestionnaire $gestionnaire=null, Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        if ($this->isCsrfTokenValid('options-'.$gestionnaire->getId(), $request->get('token'))) {
            if(!$gestionnaire) {
                $gestionnaire = new Gestionnaire();
            }

            $form = $this->createForm(RegistrationType::class, $gestionnaire);
        	$form->handlerequest($request);

        	if($form->isSubmitted() && $form->isValid()) {
        		$hash = $encoder->encodePassword($gestionnaire, $gestionnaire->getPassword());

        		$gestionnaire->setPassword($hash);
                $gestionnaire->setRoles(array("ROLE_ADMIN"));
        		$manager->persist($gestionnaire);
        		$manager->flush();
                $this->addFlash('success', 'Modifications effectuées');
        	}
        } else {
            $this->addFlash('danger', 'URL Invalide');
            return $this->redirectToRoute('connexion');
        }
 
        return $this->render('security/account.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("connexion", name="connexion") 
     */
    public function login(AuthenticationUtils $authenticationUtils) 
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('logements_show');
        }
        
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();    
        return $this->render('security/login.html.twig', [
            'error' => $error,
            'last_username' => $lastUsername
        ]);
    }

    /**
     * @Route("deconnexion", name="deconnexion")
     */
    public function logout() {}

    protected function addFlash($type, $message)
    {
        if (!$this->container->has('session')) {
            throw new \LogicException('You can not use the addFlash method if sessions are disabled.');
        }
        $this->container->get('session')->getFlashBag()->add($type, $message);
    }

}