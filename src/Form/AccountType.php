<?php

namespace App\Form;

use App\Entity\Gestionnaire;
use App\Entity\Dispositif;
use App\Entity\Ville;

use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'nom'
            ])
            ->add('dispositif', EntityType::class, [
                'class' => Dispositif::class,
                'choice_label' => 'nom'
            ])
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gestionnaire::class,
        ]);
    }
}
