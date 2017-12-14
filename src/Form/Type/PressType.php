<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints as Assert;



class PressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class);
        $builder->add('content', TextareaType::class);  
        $builder->add('link', TextType::class);  
        $builder->add('image', FileType::class,
                    array(
                        "data_class" => NULL,
                        'constraints' => array(
                            new Assert\Image(
                                array('maxSize' => '2000K')
                            )
                        )
                    )
                );


        $builder->add('Enregistrer', SubmitType::class);
    }

    
    
    public function getName()
    {
        return 'article';
    }
}
