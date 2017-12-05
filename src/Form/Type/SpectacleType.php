<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class SpectacleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class);
        $builder->add('content', TextareaType::class);
        $builder->add('dateVenue', DateTimeType::class);
        $builder->add('nbTickets', TextType::class);
        $builder->add('place', TextType::class);

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
