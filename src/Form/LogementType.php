<?php

namespace App\Form;

use App\Entity\Logement;
use App\Entity\Gestionnaire;
use App\Entity\Ville;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

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
                ->add('capacite', IntegerType::class, [
                    'attr' => ['min' => "0", 'max' => "50"]
                ])
                ->add('description', TextareaType::class, [
                    'attr' => ['max_length' => 30]
                ])
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
