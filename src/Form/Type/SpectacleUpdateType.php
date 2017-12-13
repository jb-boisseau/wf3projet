<?php

namespace WF3\Form\Type;


class SpectacleUpdateType extends SpectacleType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->remove('image');
        
    }

    
    
    public function getName()
    {
        return 'article';
    }
}
