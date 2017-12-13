<?php

namespace WF3\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class ReservationType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
    $builder



        //Email :
        ->add('email', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Votre Email'
                ),
                'constraints' => new Assert\Email()
            )) 


        //Nombre de tickets voulus :
        ->add('ticket', TextType::class, array(
            'attr' => array(
                'placeholder' => 'Entrez le nombre de places souhaitées'
            ),
            'constraints' => new Assert\Email()
            ))

           

        // Nom :
        ->add('name', TextType::class, array(
            'attr' => array(
                'placeholder' => 'Votre Nom '
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
