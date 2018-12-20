<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceFormType extends AbstractType
{
    /**
     * Get label and placeholder configuration
     * @param string $label
     * @param string $placeholder
     * @return array
     */
    private function getConfiguration($label, $placeholder)
    {
        return [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ];
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,
                $this->getConfiguration("Titre annonce", "Titre de l'annonce"))
            ->add('slug', TextType::class,
                $this->getConfiguration("Url de l'annonce", "url de l'annonce (automatique)"))
            ->add('coverImage', UrlType::class,
                $this->getConfiguration("Url de l'image", "Url de l'image principale"))
            ->add('introduction', TextType::class,
                $this->getConfiguration("Introduction", "Description globale de l'annonce"))
            ->add('content', TextareaType::class,
                $this->getConfiguration("Description", "Description détaillée"))
            ->add('rooms', IntegerType::class,
                $this->getConfiguration("Nombre de chambres", "Nombre de chambres"))
            ->add('price', MoneyType::class,
                $this->getConfiguration("Prix par nuit", "Prix par nuit"))
            ->add('images', CollectionType::class,
                [
                    'entry_type' => ImageType::class,
                    'allow_add' => true
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
