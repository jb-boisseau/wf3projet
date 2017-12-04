<?php

namespace WF3\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
//on utilise le validator de symfony
use Symfony\Component\Validator\Constraints as Assert;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('email', TextType::class, array(
                'attr' => array(
                    'placeholder' => 'Votre email',
                    'class' => 'form-control'
                ),
                'constraints' => new Assert\Email()
            ))
            ->add('phone', TextType::class)
            ->add('password', RepeatedType::class, array(
                'type'            => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre',
                'options'         => array('required' => true),
                'first_options'   => array('label' => 'Password'),
                'second_options'  => array('label' => 'Repeat password'),
                'constraints'     => array(
                                        new Assert\NotBlank(),
                                        new Assert\Length(array(
                                            'min' => 5,
                                            'max' => 12,
                                            'minMessage' => 'le mdp doit faire au moins 5 caractères',
                                            'maxMessage' => 'le mdp ne doit faire plus de 12 caractères'
                                        ))
                                    )
             ))
            ->add('role', ChoiceType::class, array(
                'choices' => array('Admin' => 'ROLE_ADMIN', 'User' => 'ROLE_USER')
            ));
    }

    public function getName()
    {
        return 'user';
    }
}
