<?php

namespace App\Form;

use App\Entity\Ville;
use App\Entity\SearchImmobilier;
use App\Entity\CategorieImmobilier;
use App\Repository\CategorieImmobilierRepository;
use App\Repository\VilleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class AdminSearchImmobilierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('q', TextType::class, [
            'label' =>  false,
            'required'  =>  false,
            'attr'  =>  [
                'placeholder'   =>  'Je cherche ...',
                'class' => '',
            ]
        ])
        ->add('statut', ChoiceType::class, [
            'label' => false,
            'help' => 'Statut',
            'required' => false,
            'choices'  => [
            	'Statut' => '',
                'A vendre'    =>  'a-vendre',
                'A louer' =>  'a-louer',
            ],
            'attr' => ['class' => ''],
            'choice_attr' => ['class' => 'checkbox'],
            'label_attr' => ['class' => 'ui-check'],
        ])
        ->add('type', ChoiceType::class, [
            'label' => false,
            'help' => 'Type',
            'required' => false,
            'choices'  => [
            	'Type' => '',
                'Maison familialle'    =>  'maison-familliale',
                'Maison unifamiliale' => 'maison-unifamiliale',
                'Maison de luxe' => 'maison-de-luxe',
                'Maison de campagne' => 'maison-compagne',
                'Autre' => 'autre',
            ],
            'attr' => ['class' => 'text-muted'],
            'choice_attr' => ['class' => 'checkbox'],
            'label_attr' => ['class' => 'ui-check'],
        ])
        ->add('minTarif', MoneyType::class, [
            'label' => false,
            'help' => 'Budget min',
            'required' => false,
            'attr' => [
                'placeholder' => 'Budget min',
            ]
        ])
        ->add('maxTarif', MoneyType::class, [
            'label' => false,
            'help' => 'Bugdet max',
            'required' => false,
            'attr' => [
                'placeholder' => 'Bugdet max',
            ]
        ])
        ->add('categories', EntityType::class, [
            'label' => False,
            'help' => "Cat??gorie",
            'multiple' => true,
            'required' => false,
            'autocomplete' => true,
                //'expanded' => true,
            'class' => CategorieImmobilier::class,
            'query_builder' => function (CategorieImmobilierRepository $getcategories) {
                return $getcategories->createQueryBuilder('c')
                ->orderBy('c.name', 'ASC');
            },
            'choice_label' => 'name',
            'attr' => ['class' => 'p-0 m-0 border-0']
        ])
        ->add('villes', EntityType::class, [
            'label' => False,
            'required' => false,
            'autocomplete' => true,
            'help' => "Ville",
            'multiple' => true,
                //'expanded' => true,
            'class' => Ville::class,
            'query_builder' => function (VilleRepository $getville) {
                return $getville->createQueryBuilder('v')
                ->orderBy('v.name', 'ASC');
            },
            'choice_label' => 'name',
            'attr' => ['class' => 'p-0 m-0 border-0']
        ])
        ->add('online', CheckboxType::class, [
            'label' => "En ligne",
            'help' => 'Visibilit??',
            'required' => false
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchImmobilier::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
