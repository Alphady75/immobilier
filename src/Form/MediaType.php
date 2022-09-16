<?php

namespace App\Form;

use App\Entity\ImmobilierMedia;
use App\Entity\Immobilier;
use App\Repository\ImmobilierRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

class MediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => false,
                'help' => 'Exemple: chambre à coucher',
            ])
            ->add('imageFile', FileType::class, [
                'label' => false,
                'help' => 'Format (jpg, jpeg et png)',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide!',
                    ]),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => false,
                'help' => 'Type de media (jpg, jpeg et png)',
                'choices'  => [
                    'Image' =>  'Image',
                    'Audio'    =>  'Audio',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide!',
                    ]),
                ],
            ])
            ->add('immobilier', EntityType::class, [
                'label' => False,
                'help' => 'Immobilier',
                'empty_data' => '',
                'class' => Immobilier::class,
                'query_builder' => function (ImmobilierRepository $getimmobilier) {
                    return $getimmobilier->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ImmobilierMedia::class,
        ]);
    }
}
