<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class LivredorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('author', TextType::class,
            array(
                'attr' => array(
                    'class'=>'form-control',
                    'placeholder'=>'Votre Nom/Pseudo'
                )
            )
        );        
        $builder->add('email', TextType::class,
            array(
                'attr' => array(
                    'class'=>'form-control',
                    'placeholder'=>'Votre Email'
                )
            )
        );
        $builder->add('content', TextareaType::class,
            array(
                'attr' => array(
                    'class'=>'form-control',
                    'placeholder'=>'Votre message...'
                )
            )
        );
        $builder->add('Enregistrer', SubmitType::class,
            array(
                'attr' => array(
                    'class'=>'btn btn-primary btn-xl text-uppercase'
                )
            )
        );
    }

    public function getName()
    {
        return 'article';
    }
}
