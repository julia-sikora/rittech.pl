<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationFormType extends AbstractType
{
    private const MIN_PASSWORD_LENGTH = 6;

    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class,
                [
                    'label' => "plant.fields.email",
                    'attr' => ['class' => "form-control input-control"],
                    'label_attr' => ['class' => "label-control label-form"]
                ]
            )
            ->add('plainPassword', PasswordType::class,
                [
                    'label_attr' => ['class' => "label-control label-form"],
                    'attr' => ['autocomplete' => 'new-password', 'class' => "form-control input-control"],
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank(['message' => $this->translator->trans('register.constraint.password.not_blank')]),
                        new Length(
                            [
                                'min' => self::MIN_PASSWORD_LENGTH,
                                'minMessage' => $this->translator->trans('register.constraint.password.length', ['%limit%' => self::MIN_PASSWORD_LENGTH]),
                                'max' => 4096
                            ]
                        )
                    ],
                ])
            ->add('appPassword', PasswordType::class,
                [
                    'label_attr' => ['class' => "label-control label-form"],
                    'label' => "plant.fields.app_password",
                    'attr' => ['class' => "form-control input-control"],
                    'mapped' => false,
                    'constraints' => [new NotBlank(['message' => $this->translator->trans('register.constraint.password.not_blank')])]
                ]
            )
            ->add('agreeTerms', CheckboxType::class,
                [
                    'required' => false,
                    'label' => "plant.fields.agree",
                    'label_attr' => ['class' => "label-control label-form"],
                    'mapped' => false,
                    'constraints' => [new IsTrue(['message' => $this->translator->trans('register.constraint.terms.is_true')])],
                ]
            )
            ->add('save', SubmitType::class,
                [
                    'label' => "plant.fields.save",
                    'attr' => ['class' => 'button save save-register']
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
