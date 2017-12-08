<?php

namespace WF3\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;



class ReservationType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //on liste les champs qu'on veut rajouter
    $builder
        ->add('email', TextType::class, array('label'=>'Entrer votre Email',
                'attr' => array(
                    'placeholder' => 'Inscrivez votre adresse EMAIL'
                ),
                'constraints' => new Assert\Email()
            )) 
            
        ->add('date', DatetimeType::class, array('label'=>'Date du Spectacle',
                'attr' => array(
                    'placeholder' => 'Entrer la DATE souhaitez'
                ),
                'constraints' => new Assert\Date()
            ))
        ->add('ticket', TextType::class, array('label'=>'Nombre de Ticket',
                'attr' => array(
                    'placeholder' => 'Entrer le nombre de place souhaitez'
                ),
                'constraints' => new Assert\Email()
                 ))
           
        ->add('name', TextType::class, array('label'=>'Entrer votre Nom',
                'attr' => array(
                    'placeholder' => 'Entrer votre NOM '
                ),
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 3,
                        'max' => 20,
                        'minMessage' => 'Le NOM doit faire au moins 3 caractères'
                    ))
                )
            ));
        
    }

    //à rajouter mais pour l'instant on s'en occupe pas
    public function getName()
    {
        return 'article';
    }
}