<?php

namespace App\Form;

use App\Entity\Song;
use App\Entity\Artist;
use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\ArtistRepository;
use App\Repository\PlaylistRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class SongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('artist', EntityType::class, [
                'class' => Artist::class,
                'choice_label' => 'name'
            ])
            ->add('playlist', EntityType::class, [
                'class' => Playlist::class,
                'query_builder' => function (PlayListRepository $er) {
                    return $er->createQueryBuilder('a')
                        ->andWhere('a.type = :type')
                        ->setParameter('type', 'Album')
                        ->orderBy('a.id', 'ASC');
                },
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => false
            ])
            ->add('song', FileType::class, [
                'label' => 'Ajouter une musique',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Song::class,
        ]);
    }
}
