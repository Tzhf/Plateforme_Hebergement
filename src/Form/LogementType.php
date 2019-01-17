<?php

namespace App\Form;

use App\Entity\Logement;
use App\Entity\Gestionnaire;
use App\Entity\Ville;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class LogementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('numRue')
                ->add('nomRue')
                ->add('ville', EntityType::class, [
                    'class' => Ville::class,
                    'choice_label' => 'nom'
                ])
                ->add('numLgmt')
                ->add('numBat')
                ->add('etage')
                ->add('typologie')
                ->add('capacite')
                ->add('description')
                ->getForm();
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Logement::class,
        ]);
    }
}
