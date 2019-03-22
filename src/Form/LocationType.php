<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Occupant;
use App\Form\OccupantType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('occupant', OccupantType::class, [
                'data_class' => Occupant::class
            ])
            ->add('dateEntree', DateType::class, array(
                'widget' => 'single_text',
                'by_reference' => true,
                'empty_data' => ''
            ))
            ->add('dateSortie', DateType::class, array(
                'widget' => 'single_text',
                'by_reference' => true,
                'empty_data' => ''
            ))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
