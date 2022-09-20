<?php

namespace App\Form;

use App\Entity\Immobilier;
use App\Entity\Ville;
use App\Entity\CategorieImmobilier;
use App\Repository\VilleRepository;
use App\Repository\CategorieImmobilierRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ImmobilierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'help' => "Example: villa à vendre sur paris",
                'label' => false,
                'attr' => ['placeholder' => 'Intitulé...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('imageFile', DropzoneType::class, [
                'label' => false,
                'help' => 'Format (jpg, jpeg et png)',
                'required' => false,
            ])
            ->add('tarif', MoneyType::class, [
                'label' => false,
                'help' => 'Tarif',
                'attr' => ['placeholder' => 'Tarif...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('reference', TextType::class, [
                'label' => false,
                'help' => 'Adresse (N° Ruelle / Avenue)',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('surfaceMin', IntegerType::class, [
                'label' => false,
                'help' => "Surface minimale",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('surfaceMax', IntegerType::class, [
                'label' => false,
                'help' => "Surface maximale",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                ],                
            ])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'help' => "Type d'immobilier",
                'choices'  => [
                    'Maison familliale' =>  'maison-familliale',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide!',
                    ]),
                ],
            ])
            ->add('description', CKEditorType::class, [
                'label' => false,
                'config' => array(
                    //'uiColor' => '#ffffff',
                    //...
                ),
            ])
            ->add('statut', ChoiceType::class, [
                'label' => false,
                'help' => 'Statut',
                'choices'  => [
                    'Maison à louer' =>  'a-louer',
                    'Maison à vendre' => 'a-vendre',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide!',
                    ]),
                ],
            ])
            ->add('online', CheckboxType::class, [
                'label' => 'Mettre en ligne',
                'required' => false,
            ])
            ->add('categorie', EntityType::class, [
                'label' => False,
                'help' => "Catégorie",
                'class' => CategorieImmobilier::class,
                'query_builder' => function (CategorieImmobilierRepository $getcategories) {
                    return $getcategories->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('ville', EntityType::class, [
                'label' => False,
                'help' => 'Ville de réféerence',
                'class' => Ville::class,
                'query_builder' => function (VilleRepository $getville) {
                    return $getville->createQueryBuilder('v')
                        ->orderBy('v.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
            ->add('climatisation', CheckboxType::class, [
                'label' => 'Climatisation',
                'required' => false,
            ])
            ->add('piscine', CheckboxType::class, [
                'label' => 'Piscine',
                'required' => false,
            ])
            ->add('chauffageCentral', CheckboxType::class, [
                'label' => 'Chauffage Central',
                'required' => false,
            ])
            ->add('spaMassages', CheckboxType::class, [
                'label' => 'Spa Massages',
                'required' => false,
            ])
            ->add('gym', CheckboxType::class, [
                'label' => 'Gym',
                'required' => false,
            ])
            ->add('alarme', CheckboxType::class, [
                'label' => 'Alarme',
                'required' => false,
            ])
            ->add('wifi', CheckboxType::class, [
                'label' => 'Wifi',
                'required' => false,
            ])
            ->add('parking', CheckboxType::class, [
                'label' => 'parking',
                'required' => false,
            ])
            ->add('chambres', IntegerType::class, [
                'label' => false,
                'help' => 'Chambres',
                'required' => false,
            ])
            ->add('salleBain', IntegerType::class, [
                'label' => false,
                'help' => 'Salle de bain',
                'required' => false,
            ])
            ->add('garage', IntegerType::class, [
                'label' => false,
                'help' => 'Garage',
                'required' => false,
            ])
            ->add('tailleGarage', IntegerType::class, [
                'label' => false,
                'help' => 'Taille Garage',
                'required' => false,
            ])
            ->add('anneeConstrunction', DateType::class, [
                'label' => false,
                'help' => 'Facultatif',
                'widget' => 'single_text',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Immobilier::class,
        ]);
    }
}
