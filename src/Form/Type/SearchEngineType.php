<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
//on utilise le validator de symfony
use Symfony\Component\Validator\Constraints as Assert;

class SearchEngineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('auteur', TextType::class, array(
                'constraints'     => array(
                                        new Assert\NotBlank(),
                                        new Assert\Length(array(
                                            'min' => 2,
                                            'minMessage' => 'le mdp doit faire au moins 5 caractères'
                                        ))
                                    )
             ))
            ->add('ordre', ChoiceType::class, array(
                'choices' => array(
                    'Du plus récent au plus ancien' => 'DESC', 
                    'Du plus ancien au plus récent' => 'ASC')
            ))
            ->add('Rechercher', SubmitType::class);
    }

    public function getName()
    {
        return 'user';
    }
}
