<?php

namespace App\Form;

use App\Entity\Plant;
use App\Entity\PlantPot;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('species', TextType::class)
            ->add('variety', TextType::class)
            ->add('toxicity', CheckboxType::class)
            ->add('dateOfPurchase', DateType::class)
            ->add('specialFeatures', TextType::class, ['required' => false])
            ->add('plantPot', EntityType::class,
                ['class' => PlantPot::class, 'choice_label' =>
                    function (PlantPot $plantPot): string {
                        return $plantPot->getProducer() . " " . $plantPot->getColour();
                    }
                ])
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plant::class,
        ]);
    }
}
