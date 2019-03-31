<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\Persistence\ObjectManager;

use App\Repository\GestionnaireRepository;
use App\Entity\Gestionnaire;
use App\Form\RegistrationType;
use App\Form\PasswordResetType;

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

        
    /**
    * @Route("reset_password", name="reset_password")
    */
    public function resetPassword(GestionnaireRepository $gestionnaire_repo, ObjectManager $manager, Request $request, \Swift_Mailer $mailer) {

        $form = $this->createFormBuilder()
            ->add('reset_email')
            ->getForm();
        $form->handlerequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $gestionnaire = $gestionnaire_repo->findOneByEmail($form->getData()['reset_email']);
            if ($gestionnaire !== null) {
                $token = uniqId();
                $gestionnaire->setResetToken($token);
                $manager->persist($gestionnaire);
                $manager->flush();

                $message = (new \Swift_Message('Plateforme Hébergement : Réinitialisation du mot de passe'))
                ->setFrom('send@example.com')
                ->setTo($form->getData()['reset_email'])
                ->setBody(
                    $this->renderView('emails/reset_password.html.twig',
                    ['id' => $gestionnaire->getId(),
                    'token' => $token
                    ]),
                    'text/html');
                $mailer->send($message);

                $this->addFlash('success', 'Message envoyé');
                return $this->redirectToRoute('reset_password');                
            } else {
                $this->addFlash('danger', 'Cette adresse email n\'est pas enregistrée');
                return $this->redirectToRoute('reset_password');                
            }
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("reset_password_token/{id}/{token}", name="reset_password_token")
    */
    public function resetPasswordToken(Gestionnaire $gestionnaire, ObjectManager $manager, Request $request, UserPasswordEncoderInterface $encoder) {
        
        if($request->get('token') == $gestionnaire->getResetToken()) {
            $form = $this->createForm(PasswordResetType::class, $gestionnaire);
        	$form->handlerequest($request);

        	if($form->isSubmitted() && $form->isValid()) {
                $hash = $encoder->encodePassword($gestionnaire, $gestionnaire->getPassword());
        		$gestionnaire->setPassword($hash);               
                $manager->persist($gestionnaire);
                $manager->flush();       
                
                $this->addFlash('success', 'Mot de passe modifié');
                return $this->redirectToRoute('connexion');
            }

        } else {
            $this->addFlash('danger', 'URL invalide');
            return $this->redirectToRoute('reset_password');                           
        }        

        return $this->render('security/reset_password_token.html.twig', [
            'form' => $form->createView()
        ]);
    }
        
    protected function addFlash($type, $message)
    {
        if (!$this->container->has('session')) {
            throw new \LogicException('You can not use the addFlash method if sessions are disabled.');
        }
        $this->container->get('session')->getFlashBag()->add($type, $message);
    }

}