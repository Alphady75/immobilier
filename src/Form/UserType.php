<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\UX\Dropzone\Form\DropzoneType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Nom'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Prénom'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                ],
            ])
            ->add('genre', ChoiceType::class, [
                'label' => false,
                'choices'  => [
                    'Homme' =>  'Homme',
                    'Femme'    =>  'Femme',
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide!',
                    ]),
                ],
            ])
            ->add('imageFile', DropzoneType::class, [
                'label' => false,
                'help' => 'Format (jpg, jpeg et png)',
                'required' => false,
            ])
            ->add('biographie', TextareaType::class, [
                'attr' => ['rows' => 10],
                'label' => false,
                'help' => 'A propos',
                'required' => false,
            ])
            ->add('adresse', TextType::class, [
                'label' => false,
                'required' => false,
                'help' => 'Exemple (N° Ruelle/Avenue)',
                'attr' => ['placeholder' => 'Adresse'],
            ])
            ->add('telephone', TextType::class, [
                'help' => 'Numéro de téléphone',
                'label' => false,
                'required' => false,
                'attr' => ['placeholder' => 'Téléphone'],
            ])
            ->add('compte', ChoiceType::class, [
                'help' => 'Choisir un role',
                'label' => false,
                'choices'  => [
                    'Utilisateur' =>  'Utilisateur',
                    'Administrateur'    =>  'Administrateur',
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide!',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'help' => 'Exemple: email@domaine.com',
                'attr' => ['placeholder' => 'Exemple@domail.com'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide',
                    ]),
                    new Email([
                        'message' => 'Veuillez saisir une adresse email valide',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                    // instead of being set onto the object directly,
                    // this is read and encoded in the controller
                'help' => 'Choisir un mot de passe (6 caractères maximum)',
                'label' => false,
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password', 'placeholder' => 'Choisir un mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit être au moins {{ limit }} caractères',
                            // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
