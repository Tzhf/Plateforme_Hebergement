<?php

namespace App\Form;

use App\Entity\Occupant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class OccupantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')
                ->add('prenom')
                ->add('dateNaissance', DateType::class, array('widget' => 'single_text', 'format' => 'yyyy-MM-dd'))
                // ->add('nbEnfant', IntegerType::class)
                ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Occupant::class,
        ]);
    }
}
