<?php

namespace App\Form;

use App\Search\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('searchText', TextType::class, ['mapped' => false, 'required' => false, 'label' => 'Mots clés (introduisez un mot à rechercher)'])
            ->add('maxPrice', IntegerType::class, ['label' => 'Prix maximal', 'required' => false])
            ->add('couleur', ChoiceType::class, [
                'choices'  => [
                    'bleu' => 'bleu',
                    'rouge' => 'rouge',
                    'vert' => 'vert',
                    'noir' => 'noir',
                    'blanc' => 'blanc',
                    'neutre' => 'neutre',
                    '' => ''
                ]])
            ->add('submit', SubmitType::class, ['label' => 'Rechercher'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class
        ]);
    }
}