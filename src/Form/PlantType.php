<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Plant;
use App\Entity\PlantPot;
use App\Repository\PlantPotRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlantType extends AbstractType
{
    public function __construct(private Security $security)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $plantId = $builder->getData()->getId();
        $builder
            ->add('species', TextType::class, ['label' => "plant.fields.species", 'attr' => ['class' => "form-control input-control"], 'label_attr' => ['class' => "label-control label-form"]])
            ->add('variety', TextType::class, ['label' => "plant.fields.variety", 'attr' => ['class' => "form-control input-control"], 'label_attr' => ['class' => "label-control label-form"]])
            ->add('toxicity', CheckboxType::class, ['required' => false, 'label' => "plant.fields.toxicity", 'label_attr' => ['class' => "label-control label-form"]])
            ->add('dateOfPurchase', DateType::class, ['label' => "plant.fields.date", 'attr' => ['class' => "form-date"], 'label_attr' => ['class' => "label-control label-form"]])
            ->add('specialFeatures', TextType::class, ['required' => false, 'label' => "plant.fields.features", 'attr' => ['class' => "form-control input-control"], 'label_attr' => ['class' => "label-control label-form"]])
            ->add('plantPot', EntityType::class,
                [
                    'class' => PlantPot::class,
                    'query_builder' =>
                        function (PlantPotRepository $plantPotRepository) use ($plantId): QueryBuilder {
                            return $plantPotRepository
                                ->createQueryBuilder('plantPot')
                                ->leftJoin('plantPot.plant', 'plant')
                                ->where('plant.id IS NULL OR plant.id = :plantId')
                                ->andWhere('plantPot.user = :user')
                                ->setParameter('plantId', $plantId)
                                ->setParameter('user', $this->security->getUser());
                        },
                    'required' => false,
                    'label' => "plant.fields.pot",
                    'attr' => ['class' => "form-control form-select"],
                    'label_attr' => ['class' => "label-control label-form"]]
            )
            ->add('save', SubmitType::class, ['label' => "plant.fields.save", 'attr' => ['class' => 'button save', 'type' => "submit"]]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plant::class,
        ]);
    }
}
