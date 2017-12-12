<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
//on utilise le validator de symfony
use Symfony\Component\Validator\Constraints as Assert;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', FileType::class,
                    array(
                        'constraints' => new Assert\Image()
                    )
                )
            ->add('upload', SubmitType::class);
            
    }

    public function getName()
    {
        return 'upload';
    }
}
