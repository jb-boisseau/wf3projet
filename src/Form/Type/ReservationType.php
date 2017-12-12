<?php

namespace WF3\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
<<<<<<< HEAD
use Symfony\Component\Form\Extension\Core\Type\DateType;
=======
>>>>>>> 567d6e72e1e87d61443e3c78c39e83a965e646a3
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;



class ReservationType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       
    $builder
<<<<<<< HEAD
        
        ->add('dateVenue', DateType::class, array('label'=>'Date du Spectacle',
                'widget' => 'single_text'
                ))
        
        ->add('email', TextType::class, array('label'=>'Entrer votre Email',
                'attr' => array(
                    'placeholder' => 'Inscrivez votre adresse EMAIL'
                ))) 
            
        ->add('nbTicket', ChoiceType::class, array('label'=>'Nombre de Ticket','choices'=> array(1=>1,2=>2,3=>3,4=>4,5=>5
                )))
=======

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
>>>>>>> 567d6e72e1e87d61443e3c78c39e83a965e646a3
           

        // Nom :
        ->add('name', TextType::class, array('label'=>'Entrer votre Nom',
<<<<<<< HEAD
                'attr' => array(
                    'placeholder' => 'Entrer votre NOM '
                )
=======
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
>>>>>>> 567d6e72e1e87d61443e3c78c39e83a965e646a3
            ));
        
    }

    //à rajouter mais pour l'instant on s'en occupe pas
    public function getName()
    {
        return 'article';
    }
}
