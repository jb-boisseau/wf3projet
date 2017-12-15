<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints as Assert;



class SpectacleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class);
        
        $builder->add('content', TextareaType::class);
          
        $builder->add('dateVenue', DateTimeType::class, [
            'date_widget'=> 'choice',
            'attr'   => ['class' => 'js-datepicker'],
        ]);
        
        $builder->add('nbTickets', TextType::class);
        
        $builder->add('place', TextType::class);
        
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
        $builder->add('price', TextType::class);
        
        $builder->add('type', ChoiceType::class, array(
            'choices' => array('Spectacle' => 'spectacle', 'Stage' => 'stage')
        ));

        $builder->add('Enregistrer', SubmitType::class);
    }

    
    
    public function getName()
    {
        return 'article';
    }
}
