<?php

namespace WF3\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;



class ReservationType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //on liste les champs qu'on veut rajouter
    $builder

        //Email :
        ->add('email', TextType::class, array('label'=>'Entrer votre Email',
                'attr' => array(
                    'placeholder' => 'Votre Email'
                ),
                'constraints' => new Assert\Email()
            )) 
            

        //Choix du Spectacle :
        ->add('spectacles', ChoiceType::class)



        //Nombre de tickets voulus :
        ->add('ticket', TextType::class, array('label'=>'Nombre de Tickets',
            'attr' => array(
                'placeholder' => 'Entrez le nombre de places souhaitées'
            ),
            'constraints' => new Assert\Email()
            ))
           

        // Nom :
        ->add('name', TextType::class, array('label'=>'Entrer votre Nom',
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