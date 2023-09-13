<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\PlantPot;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlantPotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('colour', TextType::class, ['label' => "pot.fields.colour", 'attr' => ['class' => "form-control input-control"], 'label_attr' => ['class' => "label-control label-form"]])
            ->add('producer', TextType::class, ['label' => "pot.fields.producer", 'attr' => ['class' => "form-control input-control"], 'label_attr' => ['class' => "label-control label-form"]])
            ->add('potCode', TextType::class, ['label' => "pot.fields.code", 'attr' => ['class' => "form-control input-control"], 'label_attr' => ['class' => "label-control label-form"]])
            ->add('potDiameter', NumberType::class, ['label' => "pot.fields.diameter", 'attr' => ['class' => "form-control input-control"], 'label_attr' => ['class' => "label-control label-form"]])
            ->add('save', SubmitType::class, ['label' => "pot.fields.save", 'attr' => ['class' => 'button save']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PlantPot::class,
        ]);
    }
}
