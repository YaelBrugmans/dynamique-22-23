<?php

namespace App\Form;

use App\Entity\Carte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class CarteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class)
            ->add('nom', TextType::class, ['required' => true])
            ->add('expansion', FileType::class, [
                'required' => true,
                'data_class' => null,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k'
                    ])
                ]
            ])
            ->add('image', FileType::class, [
                'required' => true,
                'data_class' => null,
                'constraints' => [
                    new Image([
                        'maxSize' => '1024k'
                    ])
                ]
            ])
            ->add('couleur', ChoiceType::class, ['required' => true,
                'choices'  => [
                    'bleu' => 'bleu',
                    'rouge' => 'rouge',
                    'vert' => 'vert',
                    'noir' => 'noir',
                    'blanc' => 'blanc',
                    'neutre' => 'neutre'
            ]])
            ->add('cout_carte', TextType::class, ['required' => true])
            ->add('artiste', TextType::class, ['required' => true])
            ->add('prix', MoneyType::class)
            ->add('atk_def', TextType::class, ['required' => true])
            ->add('save', SubmitType::class, ['label' => 'Créer une carte'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carte::class,
        ]);
    }
}
