<?php

namespace App\Form;

use App\Form\ImageType;
use App\Entity\Annonces;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('address')
            ->add('prix', MoneyType::class)
            ->add('description', TextType::class)
            ->add('coverImage', UrlType::class)//UrlType permet a symfony d'ajouter directement http lorsque l'internaute rempli le formulaire
            ->add('chambre')
            ->add('images', CollectionType::class,//on récupère dans image.php la jointure manytoOne et copie colle dans le add('')
              [
                  'entry_type'=>ImageType::class,
                  'allow_add'=>true
              ])  
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonces::class,
        ]);
    }
}
