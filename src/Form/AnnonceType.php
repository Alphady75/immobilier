<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\CategorieAnnonce;
use App\Repository\CategorieAnnonceRepository;
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

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'help' => "Example: villa à vendre sur paris",
                'label' => false,
                'attr' => ['placeholder' => 'Présentation...'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => false,
                'help' => 'Format (jpg, jpeg et png)',
                'required'  =>  false,
                'allow_delete' =>  false,
                'download_label'     =>  false,
                'image_uri'     =>  false,
                'download_uri'     =>  false,
                'imagine_pattern'   =>  'medium_size'
            ])
            ->add('description', CKEditorType::class, [
                'label' => false,
                'config' => array(
                    //'uiColor' => '#ffffff',
                    //...
                ),
            ])
            ->add('online', CheckboxType::class, [
                'label' => 'Mettre en ligne',
                'required' => false,
            ])
            ->add('categorie', EntityType::class, [
                'label' => False,
                'help' => "Catégorie",
                'empty_data' => '',
                'class' => CategorieAnnonce::class,
                'query_builder' => function (CategorieAnnonceRepository $getcategories) {
                    return $getcategories->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
