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
        
        ->add('dateVenue', DateType::class, array('label'=>'Date du Spectacle',
                'widget' => 'single_text'
                ))
        
        ->add('email', TextType::class, array('label'=>'Entrer votre Email',
                'attr' => array(
                    'placeholder' => 'Inscrivez votre adresse EMAIL'
                ))) 
            
        ->add('nbTicket', ChoiceType::class, array('label'=>'Nombre de Ticket','choices'=> array(1=>1,2=>2,3=>3,4=>4,5=>5
                )))
           
        ->add('name', TextType::class, array('label'=>'Entrer votre Nom',
                'attr' => array(
                    'placeholder' => 'Entrer votre NOM '
                )
            ));
        
    }

    //à rajouter mais pour l'instant on s'en occupe pas
    public function getName()
    {
        return 'article';
    }
}
